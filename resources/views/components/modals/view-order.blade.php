<!-- Order View Modal -->
<div
    x-data="{
        open: false,
        id: '',
        user_name: '',
        payment_method: '',
        total_amount: '',
        amount_paid: '',
        change: '',
        created_at: '',
        items: []
    }"
    x-on:open-view-modal.window="
        id = $event.detail.id;
        user_name = $event.detail.user_name;
        payment_method = $event.detail.payment_method;
        total_amount = $event.detail.total_amount;
        amount_paid = $event.detail.amount_paid;
        change = $event.detail.change;
        created_at = $event.detail.created_at;
        items = $event.detail.items;
        open = true;
    "
    x-on:close-view-modal.window="open = false"
    x-show="open"
    x-transition
    x-cloak
    wire:ignore.self
    x-on:click.self="open = false"
    class="overflow-y-auto overflow-x-hidden fixed py-6 top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
>
    <div class="relative w-full max-w-3xl max-h-full">
        <!-- Modal content -->
        <div class="px-4 py-2 relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Order Details
                </h3>
                <button 
                    type="button" 
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    x-on:click="open = false"
                >
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-4 space-y-6">
                
                <!-- Order Summary -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Order ID</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white" x-text="id"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white" x-text="user_name"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method</p>
                        <p class="text-base font-semibold uppercase text-gray-900 dark:text-white" x-text="payment_method"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Date</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white" x-text="created_at"></p>
                    </div>
                </div>

                <hr class="border-gray-200 dark:border-gray-600">

                <!-- Items Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-600 dark:text-gray-300 text-center">
                            <tr>
                                <th class="px-4 py-2">Item</th>
                                <th class="px-4 py-2">Quantity</th>
                                <th class="px-4 py-2">Price</th>
                                <th class="px-4 py-2">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="item in items" :key="item.id">
                                <tr class="text-center bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                    <td class="px-4 py-2 font-medium text-gray-900 dark:text-white" x-text="item.product_name"></td>
                                    <td class="px-4 py-2" x-text="item.quantity"></td>
                                    <td class="px-4 py-2">₱<span x-text="item.price"></span></td>
                                    <td class="px-4 py-2 font-semibold text-gray-900 dark:text-white">₱<span x-text="(item.price * item.quantity)"></span></td>
                                </tr>
                            </template>
                            <template x-if="items.length === 0">
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">No items in this order</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="mt-6 space-y-2 border-t border-gray-200 dark:border-gray-600 pt-4">
                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                        <span>Total Amount:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">₱<span x-text="Number(total_amount)"></span></span>
                    </div>
                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                        <span>Amount Paid:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">₱<span x-text="Number(amount_paid)"></span></span>
                    </div>
                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                        <span>Change:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">₱<span x-text="Number(change)"></span></span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>