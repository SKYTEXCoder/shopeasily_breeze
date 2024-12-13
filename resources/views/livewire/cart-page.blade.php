<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <div class="container mx-auto px-4">
    <h1 class="text-2xl font-semibold mb-4">Shopping Cart</h1>
    <div x-data="{price: 10000, quantity: 1, taxRate: 0.1, shipBasePrice: 10000, total(){return this.price*this.quantity}, tax(){return this.total()*this.taxRate }}" class="flex flex-col md:flex-row gap-4">
      <div class="md:w-3/4">
        <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
          <table class="w-full">
            <thead>
              <tr>
                <th class="text-left font-semibold"></th>
                <th class="text-left font-semibold">Product</th>
                <th class="text-left font-semibold">Price</th>
                <th class="text-left font-semibold">Quantity</th>
                <th class="text-left font-semibold">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><button class="rounded-full p-1 hover:bg-red-500 hover:text-white hover:border-red-700">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                  </svg>
                  
                </button></td>
                <td class="py-4">
                  <div class="flex items-center">
                    <img class="h-16 w-16 mr-4" src="https://via.placeholder.com/150" alt="Product image">
                    <span class="font-semibold">Product name</span>
                  </div>
                </td>
                <td class="py-4" >IDR <span x-text="price.toLocaleString('id-ID')"></span></td>
                <td class="py-4">
                  <div class="flex items-center">
                      <div
                      x-data="{ count: 0 }"
                      class="bg-white flex items-center rounded-md border-2 border-neutral-300"
                    >
                      <!-- Minus -->
                      <button
                        @click="quantity = Math.max(quantity-1,1)"
                        class="w-10 h-10 flex items-center justify-center text-lg font-bold text-gray-700 hover:bg-gray-100"
                      >
                        -
                      </button>
                      <div
                        class="inline-block mt-2 h-[25px] w-0.5 self-stretch bg-neutral-600 dark:bg-black/10"
                      ></div>
                      <!-- Counter -->
                      <div
                        class="w-12 h-10 flex items-center justify-center text-lg font-semibold"
                      >
                        <span x-text="quantity"></span>
                      </div>
                      <div
                        class="inline-block mt-2 h-[25px] w-0.5 self-stretch bg-neutral-600 dark:bg-black/10"
                      ></div>
                      <!-- Plus -->
                      <button
                        @click="quantity++"
                        class="w-10 h-10 flex items-center justify-center text-lg font-bold text-gray-700 hover:bg-gray-100"
                      >
                        +
                      </button>
                    </div>
                  </div>
                </td>
                <td class="py-4">IDR <span x-text="total().toLocaleString('id-ID')"></span></td>
              </tr>
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
            <span>IDR <span x-text="total().toLocaleString('id-ID')"></span></span>
          </div>
          <div class="flex justify-between mb-2">
            <span>Taxes (10%)</span>
            <span>IDR <span x-text="tax().toLocaleString('id-ID')"></span></span>
          </div>
          <div class="flex justify-between mb-2">
            <span>Shipping</span>
            <span>IDR <span x-text="shipBasePrice.toLocaleString('id-ID')"></span></span>
          </div>
          <hr class="my-2">
          <div class="flex justify-between mb-2">
            <span class="font-semibold">Total</span>
            <span class="font-semibold">IDR <span x-text="(total()+tax()+shipBasePrice).toLocaleString('id-ID')"></span></span>
          </div>
          <button class="bg-blue-500 text-base text-white py-2 px-4 rounded-lg mt-4 w-full hover:bg-blue-900">Checkout</button>
        </div>
      </div>
    </div>
  </div>
</div>