<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Payment;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Your Order Confirmation Page - TechGear E-Commerce™')]
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
        if (!$this->order_id) {
            return redirect()->route('my-orders');
        }

        if ($this->status_code && $this->transaction_status) {
            $payment = Payment::where('transaction_id', $this->order_id)->first();

            if (!$payment) {
                session()->flash('error', 'Payment record not found.');
                return redirect()->route('my-orders');
            }

            $this->latest_order = $payment->order;

            if (!$this->latest_order) {
                session()->flash('error', 'Order not found.');
                return redirect()->route('my-orders');
            }

            if (!in_array($payment->status, ['settlement', 'capture', 'pending'])) {
                $this->latest_order->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled'
                ]);
                return redirect()->route('cancelled');
            } elseif ($payment->status === 'pending') {
                $this->latest_order->update([
                    'payment_status' => 'pending',
                    'status' => 'new'
                ]);
                return redirect()->route('my-orders.show', ['order' => $this->latest_order->id]);
            } else {
                $this->latest_order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing'
                ]);
            }
        } else {
            $this->latest_order = Order::with('address')
                ->where('user_id', auth()->user()->id)
                ->where('id', $this->order_id)
                ->first();

            if (!$this->latest_order) {
                session()->flash('error', 'Order not found or access denied.');
                return redirect()->route('my-orders');
            }

            $this->latest_order->update([
                'payment_status' => 'pending',
                'status' => 'processing'
            ]);
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
