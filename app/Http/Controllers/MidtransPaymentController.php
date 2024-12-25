<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use DB;
use Http;
use Illuminate\Http\Request;
use Log;
use Str;

class MidtransPaymentController extends Controller
{
    public function create(Request $request)
    {
        $midtrans_items_details = [];
        $gross_amount = 0;
        foreach ($request->checkout_cart_items as $checkout_cart_item) {
            $gross_amount = intval(intval($gross_amount) + intval($checkout_cart_item['total_amount']));
            $midtrans_items_details[] = [
                'id' => $checkout_cart_item['product_id'],
                'price' => intval($checkout_cart_item['unit_amount']),
                'quantity' => intval($checkout_cart_item['quantity']),
                'name' => Str::limit($checkout_cart_item['name'], 40, '...'),
            ];
        }
        $midtrans_items_details[] = [
            'id' => 'shipping_' . Str::uuid(),
            'price' => intval($request->shipping_cost),
            'quantity' => 1,
            'name' => 'Shipping Costs',
        ];
        $gross_amount = intval(intval($gross_amount) + intval($request->shipping_cost));
        $midtrans_items_details[] = [
            'id' => 'tax_' . Str::uuid(),
            'price' => intval($request->tax_cost),
            'quantity' => 1,
            'name' => 'Tax Costs (1% of Subtotal)'
        ];
        $gross_amount = intval(intval($gross_amount) + intval($request->tax_cost));
        $parameters = array(
            'transaction_details' => array(
                'order_id' => Str::uuid(),
                'gross_amount' => intval($gross_amount),
            ),
            'item_details' => $midtrans_items_details,
            'customer_details' => array(
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email_address,
                'phone' => $request->phone_number,
                'billing_address' => array(
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email_address,
                    'phone' => $request->phone_number,
                    'address' => $request->street_address,
                    'city' => $request->city,
                    'postal_code' => $request->zip_code,
                    'country_code' => 'IDN',
                ),
                'shipping_address' => array(
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email_address,
                    'phone' => $request->phone_number,
                    'address' => $request->street_address,
                    'city' => $request->city,
                    'postal_code' => $request->zip_code,
                    'country_code' => 'IDN',
                )
            ),
            /* 'enabled_payments' => array(
                'credit_card',
                'bca_va',
                'bni_va',
                'bri_va' // You can specify the available payment methods that you want to provide to your customers.
            ) */
        );
        $auth = base64_encode(config('midtrans.server_key'));
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $auth,
            'Content-Type' => 'application/json',
        ])->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $parameters);
        if ($response->failed()) {
            $errorMessage = $response->body() ?: 'Failed to connect to the Midtrans Payment Gateway Snap API';
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
            ], $response->status());
        }
        $response = $response->json();
        $payment = new Payment();
        $payment->transaction_id = $parameters['transaction_details']['order_id'];
        $payment->order_id = null;
        $payment->order_cart_items = json_encode($midtrans_items_details);
        $payment->status = 'pending';
        $payment->final_price = $parameters['transaction_details']['gross_amount'];
        $payment->customer_first_name = $parameters['customer_details']['first_name'];
        $payment->customer_last_name = $parameters['customer_details']['last_name'];
        $payment->customer_email = $parameters['customer_details']['email'];
        $payment->checkout_link = $response['redirect_url'];
        $payment->snap_token = $response['token'];
        $payment->save();
        return response()->json([
            'success' => true,
            'message' => 'Successfully obtained redirect_url and snap_token from Midtrans API',
            'redirect_url' => $response["redirect_url"],
            'snap_token' => $response["token"],
            'midtrans_payment_id' => $payment->id,
        ]);
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload, false);
        if (boolval(config('midtrans.is_production')) === true) {
            $validSignatureKey = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . config('midtrans.server_key'));
            if ($notification->signature_key !== $validSignatureKey) {
                return response(['code' => 403, 'message' => 'Invalid Signaure Key'], 403);
            }
        }
        $auth = base64_encode(config('midtrans.server_key'));
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $auth,
        ])->get("https://api.sandbox.midtrans.com/v2/$request->order_id/status");
        $response = json_decode($response->body(), false);
        Log::debug(json_encode($response));
        if (!isset($response->order_id)) {
            return response(['code' => '400', 'message' => 'Invalid Response from MidTrans'], 400);
        }
        error_log($payload);
        error_log("Order ID $response->order_id: " . "Transaction Status = $response->transaction_status, Fraud Status = $response->fraud_status");
        $payment = Payment::where('transaction_id', $response->order_id)->firstOrFail();
        $order = Order::where('id', $payment->order_id)->first();
        if (!$order) {
            return response(['code' => '404', 'message' => 'Order Not Found', 404]);
        }
        if ($order->payment_status == 'paid' && $order->status == 'processing') {
            return response(['code' => '403', 'message' => 'Order Is Already Paid & Being Processed', 403]);
        }
        $paymentSuccess = false;
        if ($payment->status === 'settlement' || $payment->status === 'capture') {
            return response()->json('Payment has already been processed');
        }
        if ($response->transaction_status === 'capture') {
            if ($response->payment_type == 'credit_card') {
                if ($response->fraud_status == 'challenge') {
                    $paymentSuccess = false;
                } else {
                    $paymentSuccess = true;
                }
            }
            $payment->status = 'capture';
        } else if ($response->transaction_status === 'settlement') {
            $payment->status = 'settlement';
            $paymentSuccess = true;
        } else if ($response->transaction_status === 'pending') {
            $payment->status = 'pending';
            $paymentSuccess = false;
        } else if ($response->transaction_status === 'deny') {
            $payment->status = 'deny';
            $paymentSuccess = false;
        } else if ($response->transaction_status === 'expire') {
            $payment->status = 'expire';
            $paymentSuccess = false;
        } else if ($response->transaction_status === 'cancel') {
            $payment->status = 'cancel';
            $paymentSuccess = false;
        }
        $payment->save();
        if ($paymentSuccess) {
            DB::beginTransaction();

            try {
                $order->status = 'processing';
                $order->payment_status = 'paid';
                $order->save();
            } catch (\Exception $exception) {
                DB::rollBack();
                throw $exception;
            }
            DB::commit();
        } else {
            DB::beginTransaction();

            try {
                if ($response->transaction_status !== 'pending') {
                    $order->status = 'failed';
                    $order->payment_status = 'cancelled';
                    $order->save();
                } else {
                    //
                }
            } catch (\Exception $exception) {
                DB::rollBack();
                throw $exception;
            }
            DB::commit();
        }
        $message = 'Payment Status Is : ' . $response->transaction_status;
        return response(['code' => 200, 'message' => $message], 200);
    }
}
