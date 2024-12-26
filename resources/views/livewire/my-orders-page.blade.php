<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <h1 class="text-4xl font-bold text-slate-500">My Orders</h1>
    <div class="flex flex-col bg-white p-5 rounded mt-4 shadow-lg">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Order</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Payment
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Order
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Payment
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Order
                                    Amount</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">View
                                    Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-900 dark:even:bg-slate-800" wire:key="{{ $order->id }}">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ $order->created_at->format('d-m-Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ $payment_methods_map[$order->payment_method] ?? 'Unknown Payment Method' }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-800 dark:text-gray-200">
                                        <span class="{{ $order_status_to_color_map[$order->status] ?? 'bg-gray-500' }} py-1 px-3 rounded text-white shadow">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-800 dark:text-gray-200">
                                        <span class="{{ $order_payment_status_to_color_map[$order->payment_status] ?? 'bg-gray-500' }} py-1 px-3 rounded text-white shadow">{{ ucfirst($order->payment_status) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ Number::currency($order->grand_total ?? 0, 'IDR', 'id') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                        <a href="/my-orders/{{ $order->id }}" class="text-white py-2 px-4 rounded-md">
                                            <div class="flex flex-col items-center justify-center space-y-1">
                                                <div class="w-1 h-1 bg-gray-600 rounded-full"></div>
                                                <div class="w-1 h-1 bg-gray-600 rounded-full"></div>
                                                <div class="w-1 h-1 bg-gray-600 rounded-full"></div>
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $orders->links() }}
        </div>
    </div>
</div>
