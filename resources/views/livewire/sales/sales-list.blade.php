<div>
    <div class="mb-4 text-sm text-gray-500 dark:text-gray-400 font-medium">
        <strong class="text-gray-700 dark:text-gray-300">Filter Range:</strong> 
        <span class="ml-1">{{ $startDate->format('M d, Y') }} &rarr; {{ $endDate->format('M d, Y') }}</span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="p-3 sm:p-4 bg-green-50 dark:bg-green-900/50 rounded-xl shadow-md border border-green-100 dark:border-green-900">
            <p class="text-xs sm:text-sm font-medium text-green-700 dark:text-green-300 mb-1">Total Sales</p>
            <p class="text-xl md:text-2xl font-extrabold text-green-800 dark:text-green-200">
                <span>₱{{ number_format($totalSales, 2) }}</span>
            </p>
        </div>
        <div class="p-3 sm:p-4 bg-blue-50 dark:bg-blue-900/50 rounded-xl shadow-md border border-blue-100 dark:border-blue-900">
            <p class="text-xs sm:text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Total Orders</p>
            <p class="text-xl md:text-2xl font-extrabold text-blue-800 dark:text-blue-200">
                {{ $totalOrders }}
            </p>
        </div>
        <div class="p-3 sm:p-4 bg-yellow-50 dark:bg-yellow-900/50 rounded-xl shadow-md border border-yellow-100 dark:border-yellow-900">
            <p class="text-xs sm:text-sm font-medium text-yellow-700 dark:text-yellow-300 mb-1">Average Order</p>
            <p class="text-xl md:text-2xl font-extrabold text-yellow-800 dark:text-yellow-200">
                <span>₱{{ number_format($averageOrder, 2) }}</span>
            </p>
        </div>
        <div class="p-3 sm:p-4 bg-purple-50 dark:bg-purple-900/50 rounded-xl shadow-md border border-purple-100 dark:border-purple-900">
            <p class="text-xs sm:text-sm font-medium text-purple-700 dark:text-purple-300 mb-1">Top Payment</p>
            <p class="text-lg md:text-xl font-extrabold text-purple-800 dark:text-purple-200 uppercase">
                {{ $top_payment_method ?? 'N/A' }}
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Detailed Transactions</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 min-w-[1000px] border-collapse">
                
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr class="text-center">
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Order ID</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">User</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Customer</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Amount Paid</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Total Amount</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Change</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Payment Method</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Order Created</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Last Update</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @if ($sales->count() > 0)
                    @foreach ($sales as $sale)
                        <tr class="text-center bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                            <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                #ORD{{ $sale->id }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $sale->user->name }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $sale->customer_name ?? 'Guest' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right font-medium text-green-700 dark:text-green-400">
                                ₱{{ number_format($sale->amount_paid, 2) }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right font-bold text-gray-900 dark:text-white">
                                ₱{{ number_format($sale->total_amount, 2) }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                ₱{{ number_format($sale->change, 2) }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap uppercase">
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-semibold rounded-full shadow-sm transition-colors duration-200 uppercase {{ $sale->payment_color }}">
                                    {{ $sale->payment_method ?? 'Unknown' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600 dark:text-gray-400">
                                {{ $sale->created_at->format('M d, Y H:i A') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600 dark:text-gray-400">
                                {{ $sale->updated_at->diffForHumans() }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-xl font-medium text-gray-400 dark:text-gray-500">
                            <svg class="w-10 h-10 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                            No Sales recorded in this range.
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $sales->links() }}
        </div>

        <x-modals.edit>
            <livewire:order.edit-order-form />
        </x-modals.edit>
        <x-modals.view-order />
        <x-modals.delete />

    </div>
</div>