<x-main>
    <x-page-title text="Cashier Dashboard" />

    <x-section>

    <div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-dashboard-card title="Today's Sales" value="₱{{ number_format($todaysSales, 2) }}" />
            <x-dashboard-card title="Transactions Today" value="{{ $todaysTransactions }}" />
            <x-dashboard-card title="Average Transaction" value="₱{{ number_format($avgTransaction, 2) }}" />
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <a href="{{ route('cashier.orders') }}" class="btn-primary">View Orders</a>
            <a href="{{ route('cashier.orders.create') }}" class="btn-primary">New Order</a>
        </div>

        {{-- Payment Breakdown --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 mt-6 shadow">
            <h2 class="text-lg font-semibold mb-2">Payment Methods Today</h2>
            <div id="paymentMethodChart" class="h-64"></div>
        </div>

        {{-- Recent Orders --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 mt-6 shadow">
            <h2 class="text-lg font-semibold mb-2">Recent Orders</h2>
            <table class="min-w-full text-sm">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Date</th>
                    </tr>
                </thead>
					<tbody>
						@forelse ($recentOrders as $o)
								<tr>
								<td>#ORD{{ $o->id }}</td>
								<td>{{ $o->customer_name ?? 'Guest' }}</td>
								<td>₱{{ number_format($o->total_amount, 2) }}</td>
								<td>{{ $o->payment_method }}</td>
								<td>{{ $o->created_at->format('h:i A') }}</td>
								</tr>
						@empty
								<tr>
								<td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
										No orders yet today.
								</td>
								</tr>
						@endforelse
					</tbody>
            </table>
        </div>

    </div>

    </x-section>

</x-main>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
	let paymentLabels = @json($paymentMethods->pluck('payment_method'));
	let paymentSeries = @json($paymentMethods->pluck('total'));

	new ApexCharts(document.querySelector("#paymentMethodChart"), {
		chart: { type: 'donut', height: 250, foreColor: '#ffffff' },
		series: paymentSeries,
		labels: paymentLabels
	}).render();
</script>