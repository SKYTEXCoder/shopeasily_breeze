<?php

namespace App\Http\Controllers;

use App\Models\Payment;
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
            $gross_amount = intval($gross_amount + intval($checkout_cart_item['total_amount']));
            $midtrans_items_details[] = [
                'id' => $checkout_cart_item['product_id'],
                'price' => intval($checkout_cart_item['total_amount']),
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
        $gross_amount = intval($gross_amount + intval($request->shipping_cost));
        $midtrans_items_details[] = [
            'id' => 'tax_' . Str::uuid(),
            'price' => intval($request->tax_cost),
            'quantity' => 1,
            'name' => 'Tax Costs (1% of Subtotal)'
        ];
        $gross_amount = intval($gross_amount + intval($request->tax_cost));
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
        $response = json_decode($response->body(), true);
        $payment = new Payment();
        $payment->transaction_id = $parameters['transaction_details']['order_id'];
        $payment->order_id = $request->order_id;
        $payment->order_cart_items = json_encode($midtrans_items_details);
        $payment->status = 'pending';
        $payment->final_price = $parameters['transaction_details']['gross_amount'];
        $payment->customer_first_name = $parameters['customer_details']['first_name'];
        $payment->customer_last_name = $parameters['customer_details']['last_name'];
        $payment->customer_email = $parameters['customer_details']['email'];
        $payment->checkout_link = $response['redirect_url'];
        $payment->snap_token = $response['token'];
        $payment->save();
        return response(json_encode($response), 200);
    }

    public function webhook(Request $request)
    {
        $auth = base64_encode(config('midtrans.server_key'));

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $auth,
        ])->get("https://api.sandbox.midtrans.com/v2/$request->order_id/status");

        $response = json_decode($response->body(), true);

        $payment = Payment::where('transaction_id', $response->order_id)->firstOrFail();

        if ($payment->status === 'settlement' || $payment->status === 'capture') {
            return response()->json('Payment has already been processed');
        }

        if ($response->transaction_status === 'capture') {

            $payment->status = 'capture';

        } else if ($response->transaction_status === 'settlement') {

            $payment->status = 'settlement';

        } else if ($response->transaction_status === 'pending') {

            $payment->status = 'pending';

        } else if ($response->transaction_status === 'deny') {

            $payment->status = 'deny';

        } else if ($response->transaction_status === 'expire') {

            $payment->status = 'expire';

        } else if ($response->transaction_status === 'cancel') {

            $payment->status = 'cancel';

        }

        $payment->save();

        return response()->json('success');
    }
}
