<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('My Orders Page - ShopEasily™')]
class MyOrdersPage extends Component
{
    use WithPagination;

    public $payment_methods_map = [
        'midtrans' => 'MidTrans Payment Gateway',
        'cod' => 'Cash on Delivery (CoD)',
    ];

    public $order_status_to_color = [
        'new' => 'bg-blue-500',
        'processing' => 'bg-yellow-500',
        'shipped' => 'bg-orange-500',
        'delivered' => 'bg-green-500',
        'cancelled' => 'bg-red-500',
    ];

    public $order_payment_status_to_color = [
        'pending' => 'bg-yellow-500',
        'paid' => 'bg-green-500',
        'failed' => 'bg-red-500',
    ];

    public function render()
    {
        $my_orders = Order::where('user_id', auth()->id())->latest()->paginate(5);
        return view('livewire.my-orders-page',
        ['orders'=> $my_orders,
        'order_status_to_color_map' => $this->order_status_to_color,
        'order_payment_status_to_color_map' => $this->order_payment_status_to_color,
        'payment_methods_map' => $this->payment_methods_map]
        );
    }
}
