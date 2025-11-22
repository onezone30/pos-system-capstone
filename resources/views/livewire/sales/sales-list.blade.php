<div>
    <div class="mb-2 text-xs text-gray-500 dark:text-gray-400">
        <strong>Filter Range:</strong> 
        {{ $startDate->format('Y-m-d') }} → {{ $endDate->format('Y-m-d') }}
    </div>
    <div class="grid grid-cols-4 gap-4 mb-4">
        <div class="p-4 bg-green-100 dark:bg-green-800 rounded-lg shadow">
            <p class="text-sm text-gray-600 dark:text-gray-300">Total Sales</p>
            <p class="text-2xl font-bold text-green-700 dark:text-green-200">
                ₱{{ number_format($totalSales, 2) }}
            </p>
        </div>
        <div class="p-4 bg-blue-100 dark:bg-blue-800 rounded-lg shadow">
            <p class="text-sm text-gray-600 dark:text-gray-300">Total Orders</p>
            <p class="text-2xl font-bold text-blue-700 dark:text-blue-200">
                {{ $totalOrders }}
            </p>
        </div>
        <div class="p-4 bg-yellow-100 dark:bg-yellow-800 rounded-lg shadow">
            <p class="text-sm text-gray-600 dark:text-gray-300">Average Order</p>
            <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-200">
                ₱{{ number_format($averageOrder, 2) }}
            </p>
        </div>
        <div class="p-4 bg-purple-100 dark:bg-purple-800 rounded-lg shadow">
            <p class="text-sm text-gray-600 dark:text-gray-300">Top Payment Method</p>
            <p class="text-2xl font-bold text-purple-700 dark:text-purple-200">
                {{ $top_payment_method ?? 'N/A' }}
            </p>
        </div>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg pb-4">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-center text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Order ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        User Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Customer Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Amount Paid
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Total Amount
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Change
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Payment Method
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Order Created
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Order Updated
                    </th>
                </tr>
            </thead>
                <tbody>
                @if ($sales->count() > 0)
                    @foreach ($sales as $sale)
                        <tr class="text-center bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $sale->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $sale->user->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $sale->customer_name ?? 'Guest' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $sale->amount_paid }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $sale->total_amount }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $sale->change }}
                            </td>
                            <td class="px-6 py-4 uppercase">
                                <span class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm border border-transparent transition-colors duration-200 uppercase {{ $sale->payment_color }}">
                                    {{ $sale->payment_method ?? 'Unknown' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $sale->created_at }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $sale->updated_at->diffForHumans() }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="text-center bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th colspan="9" class="px-6 py-4 font-bold text-2xl text-gray-900 whitespace-nowrap dark:text-white">
                            No Sales
                        </th>
                    </tr>
                @endif
                </tbody>
        </table>

        <div class="mt-4">
            {{ $sales->links() }}
        </div>

        <!-- rendering modals -->
        <!-- Edit Modal -->
        <x-modals.edit>
            <livewire:order.edit-order-form />
        </x-modals.edit>
        <!-- View Modal -->
        <x-modals.view-order />
        <!-- Delete Modal -->
        <x-modals.delete />

    </div>
</div>

