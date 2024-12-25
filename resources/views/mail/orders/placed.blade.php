<x-mail::message>
# Your Order Has Been Placed Successfully!

We thank you for your order, {{ $order_customer_name }}.
Your order number is: {{ $order->id }}.

<x-mail::button :url="$url">
View Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
