<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Str;

class PaymentController extends Controller
{
    public function create(Request $request) {
        $params = array(
            'transaction_details' => array(
                'order_id' => Str::uuid(),
                'gross_amount' => $request->price,
            ),
            'item_details' => array(
                array(

                )
            ),
            'customer_details' => array(
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email_address,
                'phone' => $request->phone_number
            ),

        );
    }
}
