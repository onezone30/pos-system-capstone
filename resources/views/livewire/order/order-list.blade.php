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
                    Role
                </th>
                <th scope="col" class="px-6 py-3">
                    Customer Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Payment Method
                </th>
                <th scope="col" class="px-6 py-3">
                    Items
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
        </thead>
            <tbody>
            @if ($orders->count() > 0)
                @foreach ($orders as $order)
                    <tr class="text-center bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $order->id }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $order->user->name }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm border border-transparent transition-colors duration-200 {{ $order->user->role_color }}">
                                {{ ucfirst($order->user->role ?? 'Unknown') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $order->customer_name ?? 'Guest' }}
                        <td class="px-6 py-4 uppercase">
                            <span class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm border border-transparent transition-colors duration-200 uppercase {{ $order->payment_color }}">
                                {{ $order->payment_method ?? 'Unknown' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col items-start gap-1">
                                @foreach ($order->items as $item)
                                    <div class="flex items-center gap-1 justify-between rounded-lg bg-gray-100 dark:bg-gray-700 px-3 py-1 text-xs font-medium text-gray-800 dark:text-gray-200 shadow-sm">
                                        <span class="truncate">{{ $item->product->name ?? 'Unknown Product' }}</span>
                                        <span class="ml-auto text-gray-600 dark:text-gray-400">
                                            x{{ $item->quantity }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-1.5">
                                <x-button
                                    @click="$dispatch('open-edit-modal', {id: {{ $order->id }}})"
                                >
                                    Edit
                                </x-button>
                                <x-button 
                                    @click="$dispatch('open-delete-modal', {id: {{ $order->id }}})"
                                    color="red">
                                    Delete
                                </x-button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr class="text-center bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th colspan="8" class="px-6 py-4 font-bold text-2xl text-gray-900 whitespace-nowrap dark:text-white">
                        No Orders
                    </th>
                </tr>
            @endif
            </tbody>
    </table>

    <div class="mt-4">
        {{ $orders->links() }}
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