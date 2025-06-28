<div
    class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden h-full flex flex-col">
    <!-- Product Image -->
    <div class="relative overflow-hidden group">
        <a href="/products/{{ $product->slug }}" class="block">
            <img src="{{ url('storage', $product->images[0]) }}" alt="{{ $product->name }}"
                class="w-full h-48 sm:h-52 lg:h-56 xl:h-56 object-cover bg-gray-50 dark:bg-gray-700 transition-transform duration-300 group-hover:scale-105">
        </a>

        <!-- Sale Badge -->
        @if ($product->on_sale)
            <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-md text-xs font-medium">
                Sale
            </div>
        @endif

        <!-- Featured Badge -->
        @if ($product->is_featured)
            <div class="absolute top-2 right-2 bg-blue-500 text-white px-2 py-1 rounded-md text-xs font-medium">
                Featured
            </div>
        @endif
    </div>

    <!-- Product Info -->
    <div class="p-3 sm:p-4 flex-1 flex flex-col">
        <!-- Product Name -->
        <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-800 dark:text-gray-200 line-clamp-2 mb-2">
            <a href="/products/{{ $product->slug }}"
                class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                {{ $product->name }}
            </a>
        </h3>

        <!-- Product Description -->
        @if ($product->short_description)
            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3 flex-grow">
                {{ $product->short_description }}
            </p>
        @endif

        <!-- Price -->
        <div class="mb-3">
            @if ($product->on_sale && $product->original_price > $product->final_price)
                <div class="flex flex-col">
                    <span class="text-xs sm:text-sm text-gray-500 line-through">
                        {{ Number::currency($product->original_price, 'IDR', 'id') }}
                    </span>
                    <span class="text-base sm:text-lg lg:text-xl font-bold text-green-600 dark:text-green-400">
                        {{ Number::currency($product->final_price, 'IDR', 'id') }}
                    </span>
                </div>
            @else
                <span class="text-base sm:text-lg lg:text-xl font-bold text-gray-800 dark:text-gray-200">
                    {{ Number::currency($product->final_price, 'IDR', 'id') }}
                </span>
            @endif
        </div>

        <!-- Stock Status -->
        <div class="mb-4">
            @if ($product->in_stock)
                <span class="text-xs sm:text-sm text-green-600 dark:text-green-400 font-medium">In Stock</span>
            @else
                <span class="text-xs sm:text-sm text-red-600 dark:text-red-400 font-medium">Out of Stock</span>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col xl:flex-row gap-2 mt-auto">
            <button wire:click="addToCart" wire:loading.attr="disabled" @disabled(!$product->in_stock)
                class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-medium py-2 px-2 sm:px-3 rounded-md transition-colors duration-200 text-xs sm:text-sm">
                <div wire:loading.remove wire:target="addToCart"
                    class="flex items-center justify-center space-x-1 sm:space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="w-3 h-3 sm:w-4 sm:h-4 flex-shrink-0" viewBox="0 0 16 16">
                        <path
                            d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                    </svg>
                    <span class="truncate">
                        @if ($product->in_stock)
                            <span class="hidden sm:inline">Add to Cart</span>
                            <span class="sm:hidden">Add To Cart</span>
                        @else
                            <span class="hidden sm:inline">Out of Stock</span>
                            <span class="sm:hidden">Out Of Stock</span>
                        @endif
                    </span>
                </div>

                <div wire:loading wire:target="addToCart"
                    class="flex items-center justify-center space-x-1 sm:space-x-2">
                    <svg class="animate-spin h-3 w-3 sm:h-3 sm:w-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>
            </button>

            <a href="/products/{{ $product->slug }}"
                class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium py-2 px-2 sm:px-3 rounded-md transition-colors duration-200 text-center text-xs sm:text-sm truncate">
                <span class="hidden sm:inline">View Details</span>
                <span class="sm:hidden">View Details</span>
            </a>
        </div>
    </div>
</div>
