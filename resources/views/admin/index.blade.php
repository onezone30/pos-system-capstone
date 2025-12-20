<x-main>

    <x-page-title text="Dashboard" />

	<x-section>

    <div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- 7-Day Forecast Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-400 mb-2">7-Day Sales Forecast</h2>
                <div id="forecast7DaysChart" class="h-64"></div>
            </div>

            <!-- 30-Day Forecast Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-400 mb-2">30-Day Sales Forecast</h2>
                <div id="forecast30DaysChart" class="h-64"></div>
            </div>
        </div>


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

        {{-- Recent Transactions Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow mt-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Recent Transactions</h2>

            {{-- 💡 RESPONSIVENESS FIX: Wrap the table in a div with horizontal scroll --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Order ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">User</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Amount Paid</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Change</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Payment Method</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Date</th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @foreach ($recentTransactions as $t)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900 dark:text-white">#ORD{{ $t->id }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $t->user->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $t->customer_name ?? 'Guest' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-green-600 dark:text-green-400">{{ $t->amount_paid }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $t->change }}</td>
                                <td class="px-4 py-3 whitespace-nowrap font-semibold text-gray-900 dark:text-white">₱{{ number_format($t->total_amount, 2) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full 
                                        {{ $t->payment_method === 'Cash' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300' : 
                                        ($t->payment_method === 'Card' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                        {{ $t->payment_method }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs">{{ $t->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Optional: Add a subtle link to the full transactions page --}}
            <div class="mt-4 text-right">
                <a href="{{ route('admin.orders') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition duration-150">
                    View All Orders &rarr;
                </a>
            </div>
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
        let forecastTotals = @json($forecastData);

        new ApexCharts(document.querySelector("#topProductsChart"), {
            chart: { type: 'bar', height: 250, foreColor: '#ffffff' },
            series: [{ name: 'Sales', data: topProductTotals }],
            xaxis: { categories: topProductNames }
        }).render();

        new ApexCharts(document.querySelector("#paymentMethodChart"), {
            chart: { type: 'donut', height: 250, foreColor: '#ffffff' },
            series: paymentSeries,
            tooltip: { theme: 'dark' },
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




    let forecast7Dates = @json($forecast7['dates']);  
    let forecast7Values = @json($forecast7['values']);

    let forecast30Dates = @json($forecast30['dates']);   
    let forecast30Values = @json($forecast30['values']); 

    // ====== 7-Day Forecast Chart ======
    let options7 = {
        series: [{
            name: "Predicted Sales",
            data: forecast7Values
        }],
        chart: {
            type: 'area',
            height: 300,
            zoom: { enabled: false },
            foreColor: '#ffffff',
        },
        dataLabels: { enabled: false },
        title: {
            text: 'Predicted Daily Sales',
            align: 'left'
        },
        labels: forecast7Dates,
        xaxis: { type: 'datetime' },
        yaxis: { opposite: false },
        legend: { horizontalAlign: 'left' },
        tooltip: { theme: 'dark' },
        stroke: { width: 2, curve: 'smooth' },
        markers: { size: 4 }
    };
    new ApexCharts(document.querySelector("#forecast7DaysChart"), options7).render();

    // ====== 30-Day Forecast Chart ======
    let options30 = {
        series: [{
            name: "Predicted Sales",
            data: forecast30Values
        }],
        chart: {
            type: 'area',
            height: 300,
            zoom: { enabled: false },
            foreColor: '#ffffff',
        },
        dataLabels: { enabled: false },
        title: {
            text: 'Predicted Daily Sales',
            align: 'left'
        },
        labels: forecast30Dates,
        xaxis: { type: 'datetime' },
        yaxis: { opposite: false },
        legend: { horizontalAlign: 'left' },
        tooltip: { theme: 'dark' },
        stroke: { width: 2, curve: 'smooth' },
        markers: { size: 4 }
    };
    new ApexCharts(document.querySelector("#forecast30DaysChart"), options30).render();



</script>