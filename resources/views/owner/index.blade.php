<x-main>

    <x-page-title text="Dashboard" />

	<x-section>

    <div>

        {{-- Header --}}
        <h1 class="text-2xl font-bold text-gray-700 dark:text-gray-400 mb-4">Sales & Revenue Overview</h1>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-dashboard-card 
                title="Today's Sales" 
                value="₱{{ number_format($todaySales, 2) }}" 
            />

            <x-dashboard-card 
                title="Weekly Sales" 
                value="₱{{ number_format($weekSales, 2) }}" 
            />

            <x-dashboard-card 
                title="Monthly Sales" 
                value="₱{{ number_format($monthSales, 2) }}" 
            />

            <x-dashboard-card 
                title="Forecasted Stock-Outs" 
                value="{{ $forecastedStockOutCount }} Products" 
                :tooltip="implode(', ', array_map(fn($i) => $i['product'], $forecastedStockOuts))"
            />

            <x-dashboard-card 
                title="7-Day Forecast (Avg Daily)" 
                value="{{ round(array_sum($overallForecast['values']) / 7, 1) }} Items" 
            />

            <x-dashboard-card 
                title="Gross Revenue" 
                value="₱{{ number_format($grossRevenue, 2) }}" 
            />
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            {{-- Top 5 Selling Products --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-400 mb-2">Top 5 Selling Products</h2>
                <div id="topProductsChart" class="h-64"></div>
            </div>

            {{-- Total Payments by Method --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-400 mb-2">Total Payments by Method</h2>
                <div id="paymentMethodChart" class="h-64"></div>
            </div>
        </div>

        {{-- Recent Transactions --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow mt-6">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-400 mb-2">Recent Transactions</h2>
            <table class="min-w-full text-sm text-gray-700 dark:text-gray-400">
                <thead>
                    <tr class="border-b dark:border-gray-700">
                        <th class="text-left py-2">Order ID</th>
                        <th class="text-left py-2">User</th>
                        <th class="text-left py-2">Customer</th>
                        <th class="text-left py-2">Amount Paid</th>
                        <th class="text-left py-2">Change</th>
                        <th class="text-left py-2">Total</th>
                        <th class="text-left py-2">Payment Method</th>
                        <th class="text-left py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentTransactions as $t)
                        <tr class="border-b dark:border-gray-700">
                            <td>#ORD{{ $t->id }}</td>
                            <td>{{ $t->user->name ?? 'N/A' }}</td>
                            <td>{{ $t->customer_name ?? 'Guest' }}</td>
                            <td>{{ $t->amount_paid }}</td>
                            <td>{{ $t->change }}</td>
                            <td>₱{{ number_format($t->total_amount, 2) }}</td>
                            <td>{{ $t->payment_method }}</td>
                            <td>{{ $t->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Inventory & Stock Insights --}}
        <h1 class="text-2xl font-bold text-gray-700 dark:text-gray-400 mt-10 mb-4">Inventory & Stock Insights</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-400 mb-2">Low-Stock Alerts</h2>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-400">
                    @forelse ($lowStock as $item)
                        <li>{{ $item->product->name }} — {{ $item->quantity_stock }} units left</li>
                    @empty
                        <li>No low-stock products 🎉</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-400 mb-2">Out-of-Stock Products</h2>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-400">
                    @forelse ($outOfStock as $item)
                        <li><strong>{{ $item->product->name }}</strong> ({{ $item->product->category->name }}) -- {{ $item->size }}</li>
                    @empty
                        <li>No out-of-stock items 🎉</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- ====== STOCK-OUT LIST ====== -->
        <div class="mt-8 shadow rounded p-4">
            <h3 class="text-lg font-semibold mb-3">Products at Risk of Stock-Out (≤ 7 Days)</h3>

            <table class="w-full text-sm text-center">
                <thead >
                    <tr class="border-b text-center">
                        <th class="py-2">Product</th>
                        <th class="py-2">Size</th>
                        <th class="py-2">Stock</th>
                        <th class="py-2">Days Left</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($forecastedStockOuts as $item)
                        <tr class="border-b">
                            <td class="py-2">{{ $item['product'] }}</td>
                            <td class="py-2">{{ $item['size'] }}</td>
                            <td class="py-2">{{ $item['stock'] }}</td>
                            <td class="py-2 font-semibold text-red-600">{{ $item['days_left'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-3 text-center text-gray-500">
                                No predicted stock-outs.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow mt-6">
            <h3 class="text-lg font-semibold mb-3">Sales Trend (Actual + Forecast)</h3>
            <div id="salesTrendsChart"></div>
        </div>

        {{-- Employee Overview --}}
        <h1 class="text-2xl font-bold text-gray-700 dark:text-gray-400 mt-10 mb-4">Employee / User Overview</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-dashboard-card title="Active Cashiers on Duty" value="{{ $employeeSales->count() }}" />

            <x-dashboard-card 
                title="Top Seller (Employee)" 
                value="{{ $topSeller->name }} – ₱{{ number_format($topSeller->total_sales, 2) }}" 
            />
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow mt-6">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-400 mb-2">Sales by Employee (Leaderboard)</h2>
            <div id="salesByEmployeeChart" class="h-64"></div>
        </div>

    </div>

	</x-section>

</x-main>

{{-- ApexCharts Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
	document.addEventListener("DOMContentLoaded", function () {

        let topProductNames = @json($topProducts->pluck('product.name'));
        let topProductTotals = @json($topProducts->pluck('total'));

        let paymentLabels = @json($paymentMethods->pluck('payment_method'));
        let paymentSeries = @json($paymentMethods->pluck('total'));

        let salesDates = @json($chartDates);
        let salesTotals = @json($chartTotals);

        let employeeNames = @json($employeeNames);
        let employeeTotals = @json($employeeTotals);
        
        let forecastLabels = @json($forecastLabels);
        let forecastData = @json($forecastData);
        let forecastTotals = @json($forecastData); // alias used in chart

        // sanity checks (open console if something looks off)
        console.log('salesDates', salesDates);
        console.log('salesTotals', salesTotals);
        console.log('forecastLabels', forecastLabels);
        console.log('forecastData', forecastData);

        new ApexCharts(document.querySelector("#topProductsChart"), {
            chart: { type: 'bar', height: 250, foreColor: '#ffffff' },
            series: [{ name: 'Sales', data: topProductTotals }],
            xaxis: { categories: topProductNames }
        }).render();

        new ApexCharts(document.querySelector("#paymentMethodChart"), {
            chart: { type: 'donut', height: 250, foreColor: '#ffffff' },
            series: paymentSeries,
            labels: paymentLabels
        }).render();


        new ApexCharts(document.querySelector("#salesTrendsChart"), {
            chart: { type: 'line', height: 250, foreColor: '#ffffff' },
            series: [
                { name: 'Actual Sales', data: salesTotals },
                { name: 'Forecast', data: forecastTotals }
            ],
            xaxis: { categories: salesDates },
            yaxis: { labels: { formatter: val => `₱${val}` } },
            tooltip: { theme: 'dark' },
            stroke: { width: 2, curve: 'smooth' },
            markers: { size: 4 }
        }).render();

		// Sales by Employee

        new ApexCharts(document.querySelector("#salesByEmployeeChart"), {
            chart: { type: 'bar', height: 250, foreColor: '#ffffff' },
            series: [{ name: 'Sales', data: employeeTotals }],
            xaxis: { categories: employeeNames },
            yaxis: { labels: { formatter: val => `₱${val}` } }
        }).render();
	});
</script>