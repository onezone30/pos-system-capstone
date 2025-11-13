<x-main>

    <x-page-title text="Dashboard" />

	<x-section>

    <div>

        {{-- Header --}}
        <h1 class="text-2xl font-bold text-gray-700 dark:text-gray-400 mb-4">Sales & Revenue Overview</h1>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-dashboard-card title="Total Sales (Today)" value="₱12,540" />
            <x-dashboard-card title="Total Sales (This Week)" value="₱84,320" />
            <x-dashboard-card title="Total Sales (This Month)" value="₱356,210" />
            <x-dashboard-card title="Gross Revenue" value="₱1,284,560" />
            <x-dashboard-card title="Average Transaction" value="₱524.40" />
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
                        <th class="text-left py-2">Customer</th>
                        <th class="text-left py-2">Total</th>
                        <th class="text-left py-2">Payment Method</th>
                        <th class="text-left py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= 10; $i++)
                        <tr class="border-b dark:border-gray-700">
                            <td>#ORD{{ 1000 + $i }}</td>
                            <td>Customer {{ $i }}</td>
                            <td>₱{{ number_format(rand(100, 1000), 2) }}</td>
                            <td>{{ ['Cash', 'GCash', 'Credit'][rand(0, 2)] }}</td>
                            <td>{{ now()->subDays(rand(0, 7))->format('M d, Y') }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        {{-- Inventory & Stock Insights --}}
        <h1 class="text-2xl font-bold text-gray-700 dark:text-gray-400 mt-10 mb-4">Inventory & Stock Insights</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-400 mb-2">Low-Stock Alerts</h2>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-400">
                    <li>Milk Tea (Large) — 8 units left</li>
                    <li>Okinawa (Medium) — 5 units left</li>
                </ul>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-400 mb-2">Out-of-Stock Products</h2>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-400">
                    <li>Wintermelon (Small)</li>
                    <li>Classic Pearl (Medium)</li>
                </ul>
            </div>
        </div>

        {{-- AI-Driven Forecasting --}}
        <h1 class="text-2xl font-bold text-gray-700 dark:text-gray-400 mt-10 mb-4">AI-Driven Forecasting & Insights</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-dashboard-card title="Predicted Demand (7 Days)" value="↑ +14%" />
            <x-dashboard-card title="Predicted Demand (30 Days)" value="↑ +21%" />
            <x-dashboard-card title="Forecasted Stock-Outs" value="5 Products" />
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow mt-6">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-400 mb-2">Sales Trends (Daily)</h2>
            <div id="salesTrendsChart" class="h-64"></div>
        </div>

        {{-- Employee Overview --}}
        <h1 class="text-2xl font-bold text-gray-700 dark:text-gray-400 mt-10 mb-4">Employee / User Overview</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-dashboard-card title="Active Cashiers on Duty" value="3" />
            <x-dashboard-card title="Top Seller (Employee)" value="Maria Santos – ₱45,300" />
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
		// Top 5 Products
		new ApexCharts(document.querySelector("#topProductsChart"), {
			chart: { type: 'bar', height: 250 },
			series: [{ name: 'Sales', data: [440, 550, 480, 620, 700] }],
			xaxis: { categories: ['Okinawa', 'Wintermelon', 'Hokkaido', 'Matcha', 'Taro'] }
		}).render();

		// Payment Method
		new ApexCharts(document.querySelector("#paymentMethodChart"), {
			chart: { type: 'donut', height: 250 },
			series: [45, 35, 20],
			labels: ['Cash', 'GCash', 'Credit']
		}).render();

		// Sales Trends
		new ApexCharts(document.querySelector("#salesTrendsChart"), {
			chart: { type: 'line', height: 250 },
			series: [{
				name: 'Actual Sales',
				data: [120, 180, 150, 220, 260, 210, 300]
			}, {
				name: 'Forecasted Sales',
				data: [130, 190, 160, 240, 270, 230, 320]
			}],
			xaxis: { categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] }
		}).render();

		// Sales by Employee
		new ApexCharts(document.querySelector("#salesByEmployeeChart"), {
			chart: { type: 'bar', height: 250 },
			series: [{ name: 'Sales', data: [45300, 38200, 32750, 29500] }],
			xaxis: { categories: ['Maria Santos', 'John Cruz', 'Angela Tan', 'Ryan Dela Cruz'] }
		}).render();
	});
</script>