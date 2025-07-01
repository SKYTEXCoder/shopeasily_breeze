<div class="w-full max-w-[85rem] xl:max-w-[95rem] 2xl:max-w-[120rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <section class="py-10 bg-gray-50 font-poppins dark:bg-gray-800 rounded-lg">
        <div class="px-4 py-4 mx-auto max-w-full lg:py-6 md:px-6">
            <!-- Search Results Header -->
            @if(!empty($search))
                <div class="mb-6 p-4 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        Search Results for: "{{ $search }}"
                    </h1>
                    @if(!empty($category))
                        @php
                            $selectedCategoryName = $categories->firstWhere('id', $category)?->name;
                        @endphp
                        @if($selectedCategoryName)
                            <p class="text-gray-600 dark:text-gray-400">
                                in category: <span class="font-semibold">{{ $selectedCategoryName }}</span>
                            </p>
                        @endif
                    @endif
                </div>
            @endif

            <div class="flex flex-wrap mb-24 -mx-3">
                <div class="w-full pr-2 lg:w-1/4 xl:w-1/5 2xl:w-1/6 lg:block">
                    <!-- Make sidebar sticky and scrollable -->
                    <div class="sticky top-20 h-[calc(100vh-5rem)] overflow-y-auto">
                        <div class="p-4 mb-5 bg-white border border-gray-200 dark:border-gray-900 dark:bg-gray-900">
                            <h2 class="text-2xl font-bold dark:text-gray-400"> Categories</h2>
                            <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                            <ul>
                                @foreach ($categories as $category)
                                    <li class="mb-4" wire:key="{{ $category->id }}">
                                        <label for="{{ $category->slug }}" class="flex items-center dark:text-gray-400 ">
                                            <input type="checkbox" wire:model.live="selected_categories"
                                                id="{{ $category->slug }}" value="{{ $category->id }}" class="w-4 h-4 mr-2">
                                            <span class="text-lg">{{ $category->name }}</span>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="p-4 mb-5 bg-white border border-gray-200 dark:bg-gray-900 dark:border-gray-900">
                            <h2 class="text-2xl font-bold dark:text-gray-400">Brands</h2>
                            <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                            <ul>
                                @foreach ($brands as $brand)
                                    <li class="mb-4" wire:key="{{ $brand->id }}">
                                        <label for="{{ $brand->slug }}" class="flex items-center dark:text-gray-300">
                                            <input type="checkbox" wire:model.live="selected_brands"
                                                id="{{ $brand->slug }}" value="{{ $brand->id }}" class="w-4 h-4 mr-2">
                                            <span class="text-lg dark:text-gray-400">{{ $brand->name }}</span>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="p-4 mb-5 bg-white border border-gray-200 dark:bg-gray-900 dark:border-gray-900">
                            <h2 class="text-2xl font-bold dark:text-gray-400">Product Status</h2>
                            <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                            <ul>
                                <li class="mb-4">
                                    <label for="on_sale" class="flex items-center dark:text-gray-300">
                                        <input type="checkbox" wire:model.live="on_sale" id="on_sale" value="1"
                                            class="w-4 h-4 mr-2">
                                        <span class="text-lg dark:text-gray-400">On Sale</span>
                                    </label>
                                </li>
                                <li class="mb-4">
                                    <label for="featured" class="flex items-center dark:text-gray-300">
                                        <input type="checkbox" id="featured" wire:model.live="featured" value="1"
                                            class="w-4 h-4 mr-2">
                                        <span class="text-lg dark:text-gray-400">Featured</span>
                                    </label>
                                </li>
                                <li class="mb-4">
                                    <label for="in_stock" class="flex items-center dark:text-gray-300">
                                        <input type="checkbox" id="in_stock" value="1" wire:model.live="in_stock"
                                            class="w-4 h-4 mr-2">
                                        <span class="text-lg dark:text-gray-400">In Stock</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <div class="p-4 mb-5 bg-white border border-gray-200 dark:bg-gray-900 dark:border-gray-900">
                            <h2 class="text-2xl font-bold dark:text-gray-400">Price</h2>
                            <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                            <div>
                                <div class="font-semibold">Harga Maksimum:
                                    {{ Number::currency($price_range, 'IDR', 'id') }}</div>
                                <input type="range" wire:model.live="price_range"
                                    class="w-full h-1 mb-4 bg-blue-100 rounded appearance-none cursor-pointer"
                                    max="{{ $max_price_of_queried_products }}"
                                    value="{{ $max_price_of_queried_products }}" step="5000">
                                <div class="flex justify-between ">
                                    <span
                                        class="inline-block text-sm font-bold text-blue-400 ">{{ Number::currency(0, 'IDR', 'id') }}</span>
                                    <span
                                        class="inline-block text-sm font-bold text-blue-400 ">{{ Number::currency($max_price_of_queried_products, 'IDR', 'id') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full px-3 lg:w-3/4 xl:w-4/5 2xl:w-5/6">
                    <div class="px-3 mb-4">
                        <div
                            class="items-center justify-between hidden px-3 py-2 bg-gray-100 md:flex dark:bg-gray-900 ">
                            <div class="flex items-center justify-between">
                                <select wire:model.live="sort"
                                    class="block w-41 text-base bg-gray-100 cursor-pointer dark:text-gray-400 dark:bg-gray-900">
                                    <option value="latest">Sort by: Recently Added</option>
                                    <option value="oldest">Sort by: Earliest Added</option>
                                    <option value="price-ascending">Sort by: Price (Lowest to Highest)</option>
                                    <option value="price-descending">Sort by: Price (Highest to Lowest)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Products Listing Grid -->
                    <div class="flex flex-wrap items-center ">
                        @if (!$hasProducts)
                            <div class="w-full text-center py-16">
                                <span class="text-lg text-gray-500 dark:text-gray-400">
                                    There are currently no products matching your browse or search parameters in the
                                    system's database.
                                </span>
                            </div>
                        @else
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4 sm:gap-6 w-full">
                                @foreach ($products as $product)
                                    <livewire:product-listing-card :product="$product" :key="$product->id" />
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <!-- pagination start -->
                    <div class="flex justify-end mt-6">
                        {{ $products->links() }}
                    </div>
                    <!-- pagination end -->
                </div>
            </div>
        </div>
    </section>
</div>



<!--<nav aria-label="page-navigation">
    <ul class="flex list-style-none">
        <li class="page-item disabled ">
            <a href="#"
                class="relative block pointer-events-none px-3 py-1.5 mr-3 text-base text-gray-700 transition-all duration-300  rounded-md dark:text-gray-400 hover:text-gray-100 hover:bg-blue-600">Previous
            </a>
        </li>
        <li class="page-item ">
            <a href="#"
                class="relative block px-3 py-1.5 mr-3 text-base hover:text-blue-700 transition-all duration-300 hover:bg-blue-200 dark:hover:text-gray-400 dark:hover:bg-gray-700 rounded-md text-gray-100 bg-blue-400">1
            </a>
        </li>
        <li class="page-item ">
            <a href="#"
                class="relative block px-3 py-1.5 text-base text-gray-700 transition-all duration-300 dark:text-gray-400 dark:hover:bg-gray-700 hover:bg-blue-100 rounded-md mr-3  ">2
            </a>
        </li>
        <li class="page-item ">
            <a href="#"
                class="relative block px-3 py-1.5 text-base text-gray-700 transition-all duration-300 dark:text-gray-400 dark:hover:bg-gray-700 hover:bg-blue-100 rounded-md mr-3 ">3
            </a>
        </li>
        <li class="page-item ">
            <a href="#"
                class="relative block px-3 py-1.5 text-base text-gray-700 transition-all duration-300 dark:text-gray-400 dark:hover:bg-gray-700 hover:bg-blue-100 rounded-md ">Next
            </a>
        </li>
    </ul>
</nav>-->
