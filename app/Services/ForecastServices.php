<?php

namespace App\Services;

use App\Models\SalesHistory;
use App\Models\ProductPrices;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ForecastServices
{
    /**
     * Return daily aggregated sales for a product (or overall when $productId null)
     * for the last $days days (including today) as [ 'YYYY-mm-dd' => qty, ... ].
     */
    public function dailyHistory(?int $productId = null, int $days = 60): array
    {
        $start = now()->subDays($days - 1)->startOfDay()->toDateString();

        $query = SalesHistory::selectRaw('date, SUM(quantity_sold) as qty')
            ->where('date', '>=', $start)
            ->groupBy('date')
            ->orderBy('date');

        if ($productId) {
            $query->where('product_id', $productId);
        }

        $rows = $query->get()->pluck('qty', 'date')->toArray();

        // Ensure continuous days, fill missing with zero
        $series = [];
        for ($i = 0; $i < $days; $i++) {
            $d = Carbon::now()->subDays($days - 1 - $i)->toDateString();
            $series[$d] = isset($rows[$d]) ? (int)$rows[$d] : 0;
        }

        return $series;
    }

    /**
     * Simple Moving Average forecast.
     * $window = number of trailing days to average.
     * returns forecast value for next day (float).
     */
    public function smaForecast(array $history, int $window = 7): float
    {
        $values = array_values($history);
        if (count($values) === 0) return 0.0;

        $window = min($window, count($values));
        $slice = array_slice($values, -$window);
        return array_sum($slice) / max(1, count($slice));
    }

    /**
     * Single exponential smoothing forecast for next day.
     * alpha in (0,1). If no data returns 0.
     */

    /**
     * Exponential smoothing forecast (revenue-based)
     */
    public function expForecast(array $history, float $alpha = 0.5, int $horizon = 7): array
    {
        $values = array_values($history);
        if (empty($values)) return array_fill(0, $horizon, 0.0);

        // initial level
        $s = $values[0];

        foreach ($values as $x) {
            $s = $alpha * $x + (1 - $alpha) * $s;
        }

        $forecast = [];
        for ($h = 1; $h <= $horizon; $h++) {
            // iterative: simple method for demonstration
            $forecast[] = $s;
        }

        return $forecast;
    }

    public function forecastRevenueSeries(int $historyDays = 60, int $horizon = 7, float $alpha = 0.5): array
    {
        $history = \App\Models\Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('created_at', '>=', now()->subDays($historyDays - 1))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();




        // Fill missing days
        $series = [];
        for ($i = 0; $i < $historyDays; $i++) {
            $d = now()->subDays($historyDays - 1 - $i)->toDateString();
            $series[$d] = $history[$d] ?? 0;
        }

        $forecastValues = $this->expSmoothingSeries($series, $horizon, $alpha, 0.2);

        // Fix: assign array_keys to a variable before passing to end()
        $keys = array_keys($series);
        $lastDate = Carbon::parse(end($keys));

        $forecastDates = [];
        for ($i = 1; $i <= $horizon; $i++) {
            $forecastDates[] = $lastDate->copy()->addDays($i)->format('Y-m-d');
        }

        return ['dates' => $forecastDates, 'values' => $forecastValues];
    }

    public function expSmoothingSeries(array $history, int $horizon = 7, float $alpha = 0.5, float $beta = 0.3): array
    {
        $values = array_values($history);
        $n = count($values);

        // If not enough data to establish a trend, return flat line or zeros
        if ($n < 2) {
            return array_fill(0, $horizon, $n > 0 ? end($values) : 0);
        }

        // 1. Initialize Level (L) and Trend (T)
        // Level is the first value
        $level = $values[0];
        // Trend is the difference between the first two values (initial slope)
        $trend = $values[1] - $values[0];

        // 2. "Learn" the trend from history
        // We start loop at index 1 because we already used index 0 for initialization
        for ($i = 1; $i < $n; $i++) {
            $lastLevel = $level;
            $lastTrend = $trend;
            $currentVal = $values[$i];

            // Update Level: Alpha * Current + (1-Alpha) * (PrevLevel + PrevTrend)
            $level = $alpha * $currentVal + (1 - $alpha) * ($lastLevel + $lastTrend);

            // Update Trend: Beta * (NewLevel - PrevLevel) + (1-Beta) * PrevTrend
            $trend = $beta * ($level - $lastLevel) + (1 - $beta) * $lastTrend;
        }

        // 3. Forecast future values
        $forecast = [];
        for ($h = 1; $h <= $horizon; $h++) {
            // The Formula: Forecast = Level + (StepsAhead * Trend)
            $prediction = $level + ($h * $trend);

            // Optional: Prevent negative sales predictions
            $forecast[] = max(0, $prediction);
        }

        return $forecast;
    }
    /**
     * Linear regression forecast (least squares).
     * Returns forecast for next $horizon days as array of floats.
     * $history is date => value, ordered ascending by date.
     */
    public function linearRegressionForecast(array $history, int $horizon = 1): array
    {
        $values = array_values($history);
        $n = count($values);
        if ($n === 0) return array_fill(0, $horizon, 0.0);
        if ($n === 1) return array_fill(0, $horizon, (float)$values[0]);

        // x = 0..n-1, y = values
        $xSum = ($n - 1) * $n / 2;
        $ySum = array_sum($values);
        $x2Sum = ($n - 1) * $n * (2 * $n - 1) / 6; // sum(i^2)
        $xySum = 0;
        for ($i = 0; $i < $n; $i++) {
            $xySum += $i * $values[$i];
        }

        $den = ($n * $x2Sum) - ($xSum * $xSum);
        if (abs($den) < 1e-9) {
            // fallback: no variance in x
            $avg = $ySum / $n;
            return array_fill(0, $horizon, (float)$avg);
        }

        $slope = (($n * $xySum) - ($xSum * $ySum)) / $den;
        $intercept = ($ySum - $slope * $xSum) / $n;

        $forecasts = [];
        for ($h = 1; $h <= $horizon; $h++) {
            $xi = $n - 1 + $h;
            $forecasts[] = $intercept + $slope * $xi;
        }

        return array_map(fn($v) => max(0.0, (float)$v), $forecasts);
    }

    /**
     * Build forecast series for next $horizon days using chosen method.
     * method: 'sma' | 'exp' | 'lr' (linear regression)
     * returns [ 'dates' => [...], 'values' => [...] ] where dates are ISO strings.
     */
    public function forecastSeries(?int $productId = null, int $historyDays = 60, int $horizon = 7, string $method = 'exp', array $opts = []): array
    {
        $history = $this->dailyHistory($productId, $historyDays);
        $dates = array_keys($history);
        $lastDate = Carbon::parse(end($dates) ?: now()->toDateString());

        $values = [];
        if ($method === 'sma') {
            $window = $opts['window'] ?? 7;
            for ($h = 1; $h <= $horizon; $h++) {
                // naive iterative SMA: append previous forecast then recompute
                $forecast = $this->smaForecast($history, $window);
                $values[] = $forecast;
                // append forecast to history for iterative horizon
                $history[$lastDate->copy()->addDays($h)->toDateString()] = $forecast;
            }
        } elseif ($method === 'exp') {
            $alpha = $opts['alpha'] ?? 0.3;
            $s = array_values($history);
            if (empty($s)) return array_fill(0, $horizon, 0.0);

            $lastSmoothed = $s[0];
            foreach ($s as $x) {
                $lastSmoothed = $alpha * $x + (1 - $alpha) * $lastSmoothed;
            }

            for ($h = 0; $h < $horizon; $h++) {
                $values[] = $lastSmoothed;
                // feed previous forecast as next input
                $lastSmoothed = $alpha * $lastSmoothed + (1 - $alpha) * $lastSmoothed;
            }
        } else { // linear regression
            $values = $this->linearRegressionForecast($history, $horizon);
        }

        $forecastDates = [];
        for ($i = 1; $i <= $horizon; $i++) {
            $forecastDates[] = $lastDate->copy()->addDays($i)->format('Y-m-d');
        }

        return ['dates' => $forecastDates, 'values' => $values];
    }

    /**
     * Predict stock-outs using forecasted daily demand.
     * For each ProductPrices entry, compute forecasted average daily demand (use product-level forecast)
     * and estimate days until empty = quantity_stock / forecastDaily.
     *
     * Returns array of records:
     * [
     *   'product_id' => int,
     *   'product' => 'Name',
     *   'size' => 'Large',
     *   'stock' => int,
     *   'forecast_daily' => float,
     *   'days_left' => float
     * ]
     */
    public function predictStockOuts(int $horizon = 30, float $alpha = 0.5, int $historyDays = 60, float $thresholdDays = 7): array
    {
        $results = [];
        // Eager load product to avoid N+1 queries
        $prices = \App\Models\ProductPrices::with('product')->get();

        foreach ($prices as $price) {
            $history = $this->dailyHistory($price->product_id, $historyDays);

            // Calculate forecast
            $forecast = $this->expSmoothingSeries($history, $horizon, $alpha, 0.2);

            // 1. FIX: Prevent division by zero if forecast is empty
            if (empty($forecast)) {
                continue;
            }

            // Calculate average daily sales from forecast
            $totalForecast = array_sum($forecast);
            $count = count($forecast);

            // 2. Extra safety: ensure count is > 0 (redundant if empty() check passes, but good practice)
            if ($count === 0) {
                continue;
            }

            $avgDaily = $totalForecast / $count;

            // 3. Prevent division by zero in the stock calculation
            // If predicted sales are 0 (or negative), we will never run out of stock.
            if ($avgDaily <= 0.001) {
                continue;
            }

            $daysLeft = $price->quantity_stock / $avgDaily;

            if ($daysLeft <= $thresholdDays) {
                $results[] = [
                    'product_id' => $price->product_id,
                    'product' => $price->product->name ?? 'Unknown',
                    'size' => $price->size,
                    'stock' => $price->quantity_stock,
                    'forecast_daily' => round($avgDaily, 2),
                    'days_left' => round($daysLeft, 1),
                ];
            }
        }

        usort($results, fn($a, $b) => $a['days_left'] <=> $b['days_left']);

        return $results;
    }
}
