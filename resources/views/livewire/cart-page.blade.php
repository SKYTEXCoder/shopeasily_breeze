<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-4">Shopping Cart</h1>
        <div class="flex flex-col md:flex-row gap-4">
            <div class="md:w-3/4">
                <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
                    <table class="w-full border-separate border-spacing-4">
                        <thead>
                            <tr>
                                <th class="text-center font-semibold"></th>
                                <th class="text-center font-semibold">Product Name</th>
                                <th class="text-center font-semibold">Price</th>
                                <th class="text-center font-semibold">Quantity</th>
                                <th class="text-center font-semibold">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cart_items as $item)
                                <tr wire:key='{{ $item['product_id'] }}'>
                                    <td class="py-4 px-2"><button
                                            class="rounded-full p-1 hover:bg-red-500 hover:text-white hover:border-red-700"
                                            wire:click="removeItem({{ $item['product_id'] }})">
                                            <svg wire:loading.remove wire:target="removeItem({{ $item['product_id'] }})"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                            <div wire:loading wire:target="removeItem({{ $item['product_id'] }})"
                                                class="spinner"></div>
                                        </button></td>
                                    <td class="py-4 px-2">
                                        <div class="flex items-center">
                                            <img class="h-16 w-16 mr-4" src="{{ url('storage', $item['image']) }}"
                                                alt="{{ $item['name'] }}">
                                            <span class="font-semibold">{{ $item['name'] }}</span>
                                        </div>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-4xl font-semibold text-slate-500">
                                        There are currently no products/items in your cart.</td>
                                </tr>
                            @endforelse

                            <!-- More product rows -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="md:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4">Summary</h2>
                    <div class="flex justify-between mb-2">
                        <span>Subtotal</span>
                        <span>{{ Number::currency($grand_total, 'IDR', 'id') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Taxes (10% of Subtotal)</span>
                        <span>{{ Number::currency($grand_total * 0.1, 'IDR', 'id') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Shipping</span>
                        @if ($cart_items)
                            <span>{{ Number::currency(28000, 'IDR', 'id') }}</span>
                        @else
                            <span>{{ Number::currency(0, 'IDR', 'id') }}</span>
                        @endif

                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold">Grand Total</span>
                        @if ($cart_items)
                            <span
                                class="font-semibold">{{ Number::currency($grand_total * 1.1 + 28000, 'IDR', 'id') }}</span>
                        @else
                            <span class="font-semibold">{{ Number::currency(0, 'IDR', 'id') }}</span>
                        @endif
                    </div>
                    @if ($cart_items)
                        <button
                            class="bg-blue-500 text-base text-white py-2 px-4 rounded-lg mt-4 w-full hover:bg-blue-900">Check
                            Out</button>
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

<!-- TODO: 1: Maybe make it so that a loading animation appears when the customer clicks the remove button DONE
    2: Figure out how to properly send the user's cart data to the checkout page when they click the checkout button-->
