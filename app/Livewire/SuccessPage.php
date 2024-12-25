<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Payment;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Order Confirmation Page - ShopEasily™')]
class SuccessPage extends Component
{
    #[Url]
    public $order_id;
    #[Url]
    public $status_code;
    #[Url]
    public $transaction_status;

    public $shipping_couriers = [
        'jne' => 'JNE',
        'tiki' => 'TIKI',
        'pos_indonesia' => 'POS Indonesia',
    ];

    public function render()
    {
        $latest_order = Order::with('address')->where('user_id', auth()->user()->id)->latest()->first();

        if ($this->order_id && $this->status_code && $this->transaction_status) {
            $payment = Payment::where('transaction_id', $this->order_id)->first();

            if ($payment->status !== 'settlement' || $payment->status !== 'capture') {
                $latest_order->payment_status = 'failed';
                $latest_order->status = 'cancelled';
                $latest_order->save();
                return redirect()->route('cancelled');
            } else {

            }
        }



        return view('livewire.success-page', [
            'order' => $latest_order,
            'shipping_couriers' => $this->shipping_couriers,
        ]);
    }
}
