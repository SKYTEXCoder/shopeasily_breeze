<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-4">Your Shopping Cart</h1>
        <div class="flex flex-col md:flex-row gap-4">
            <div class="md:w-3/4">
                <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
                    <table class="w-full border-separate border-spacing-4">
                        @if (!empty($cart_items))
                            <thead>
                                <tr>
                                    <span class="text-sm text-center font-semibold text-gray-600">Select All</span>
                                    <th class="text-center font-semibold">
                                        <div class="flex flex-col items-center">
                                            <input type="checkbox" wire:model.live="selected_all_cart_items"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </div>
                                    </th>
                                    <th class="text-center font-semibold">Product Name</th>
                                    <th class="text-center font-semibold">Unit Price</th>
                                    <th class="text-center font-semibold">Quantity</th>
                                    <th class="text-center font-semibold">Total Price</th>
                                    <th class="text-center font-semibold">
                                        <button class="hover:text-red-600"
                                            wire:click="removeAllSelectedCartItems()">
                                            <span class="fa-solid fa-trash" wire:loading.remove
                                                wire:target="removeAllSelectedCartItems()"></span>
                                            <div wire:loading wire:target="removeAllSelectedCartItems()" class="spinner"></div>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                        @endif
                        <tbody>
                            @forelse ($cart_items as $item)
                                <tr wire:key='{{ $item['product_id'] }}'>
                                    <td class="py-4 px-2 text-center">
                                        <input type="checkbox" wire:model.live="selected_cart_items"
                                            value="{{ $item['product_id'] }}"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    </td>
                                    <td class="py-4 px-2">
                                        <a wire:navigate href="{{ url('products/' . $item['slug']) }}">
                                            <div class="flex items-center">
                                                <img class="h-16 w-16 mr-4" src="{{ url('storage', $item['image']) }}"
                                                    alt="{{ $item['name'] }}">
                                                <span class="font-semibold">{{ $item['name'] }}</span>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="py-4 px-2">{{ Number::currency($item['unit_amount'], 'IDR', 'id') }}</td>
                                    <td class="py-4 px-2">
                                        <div class="flex items-center">
                                            <div
                                                class="bg-white flex items-center rounded-md border-2 border-neutral-300">
                                                <!-- Minus -->
                                                <button wire:click="decreaseQty({{ $item['product_id'] }})"
                                                    class="w-10 h-10 flex items-center justify-center text-lg font-bold text-gray-700 hover:bg-gray-100">
                                                    -
                                                </button>
                                                <div
                                                    class="inline-block mt-2 h-[25px] w-0.5 self-stretch bg-neutral-600 dark:bg-black/10">
                                                </div>
                                                <!-- Counter -->
                                                <div
                                                    class="w-12 h-10 flex items-center justify-center text-lg font-semibold">
                                                    {{ $item['quantity'] }}
                                                </div>
                                                <div
                                                    class="inline-block mt-2 h-[25px] w-0.5 self-stretch bg-neutral-600 dark:bg-black/10">
                                                </div>
                                                <!-- Plus -->
                                                <button wire:click="increaseQty({{ $item['product_id'] }})"
                                                    class="w-10 h-10 flex items-center justify-center text-lg font-bold text-gray-700 hover:bg-gray-100">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-2">
                                        {{ Number::currency($item['total_amount'], 'IDR', 'id') }}
                                    </td>
                                    <td class="py-4 px-2"><button
                                            class="rounded-full p-1 hover:bg-red-500 hover:text-white hover:border-red-700"
                                            wire:click="removeItem({{ $item['product_id'] }})">
                                            <svg wire:loading.remove
                                                wire:target="removeItem({{ $item['product_id'] }})"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                            <div wire:loading wire:target="removeItem({{ $item['product_id'] }})"
                                                class="spinner"></div>
                                        </button></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-4xl font-semibold text-slate-500">
                                        There are currently no products/items <br>in your shopping cart.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="md:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-[80px]">
                    <h2 class="text-lg font-semibold mb-4">Summary</h2>
                    <div class="flex justify-between mb-2">
                        <span>Sub Total</span>
                        <span>{{ Number::currency($grand_total, 'IDR', 'id') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Taxes ({{ $tax_percentage * 100 }}%)</span>
                        <span>{{ Number::currency($tax_cost, 'IDR', 'id') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Shipping Costs</span>
                        @if ($selected_cart_items)
                            <span>{{ Number::currency($shipping_cost, 'IDR', 'id') }}</span>
                        @else
                            <span>{{ Number::currency(0, 'IDR', 'id') }}</span>
                        @endif

                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold">Grand Total</span>
                        @if ($selected_cart_items)
                            <span
                                class="font-semibold">{{ Number::currency($ultimate_grand_total, 'IDR', 'id') }}</span>
                        @else
                            <span class="font-semibold">{{ Number::currency(0, 'IDR', 'id') }}</span>
                        @endif
                    </div>
                    @if ($selected_cart_items)
                        <a wire:navigate href="{{ url('checkout') }}?selected_cart_items={{ urlencode(json_encode($selected_cart_items)) }}">
                            <button
                                class="bg-blue-500 text-base text-white py-2 px-4 rounded-lg mt-4 w-full hover:bg-blue-900">Check
                                Out</button>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <style>
        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</div>

<!-- TODO: 1: Maybe make it so that a loading animation appears when the customer clicks the remove button (and/or any of the other buttons that requires the server to send a response back to the client before updating the front-end) DONE
    2: Figure out how to properly send the customer's cart items data (with respect to the selected cart items) to the checkout page when they click the checkout button-->
