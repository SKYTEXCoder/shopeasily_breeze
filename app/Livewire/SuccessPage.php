<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Payment;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Your Order Confirmation Page - ShopEasily™')]
class SuccessPage extends Component
{
    #[Url]
    public $order_id;
    #[Url]
    public $status_code;
    #[Url]
    public $transaction_status;
    public $latest_order;
    public $shipping_couriers = [
        'jne' => 'JNE',
        'tiki' => 'TIKI',
        'pos_indonesia' => 'POS Indonesia',
    ];
    public function mount()
    {
        $this->latest_order = Order::with('address')
            ->where('user_id', auth()->user()->id)
            ->latest()
            ->first();

        if ($this->order_id && $this->status_code && $this->transaction_status) {
            $payment = Payment::where('transaction_id', $this->order_id)->first();
            $this->latest_order = $payment->order;
            if (!in_array($payment->status, ['settlement', 'capture', 'pending'])) {
                $this->latest_order->payment_status = 'failed';
                $this->latest_order->status = 'cancelled';
                $this->latest_order->save();
                return redirect()->route('cancelled');
            } elseif ($payment->status === 'pending') {
                $this->latest_order->payment_status = 'pending';
                $this->latest_order->status = 'new';
                $this->latest_order->save();
                return redirect()->route('my-orders.show', ['order' => $this->latest_order->id]);
            } else {
                $this->latest_order->payment_status = 'paid';
                $this->latest_order->status = 'processing';
                $this->latest_order->save();
            }
        } else {
            $this->latest_order->payment_status = 'pending';
            $this->latest_order->status = 'processing';
            $this->latest_order->save();
        }
    }
    public function render()
    {
        return view('livewire.success-page', [
            'order' => $this->latest_order,
            'shipping_couriers' => $this->shipping_couriers,
        ]);
    }
}
