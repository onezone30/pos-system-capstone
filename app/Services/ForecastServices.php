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

        $forecastValues = $this->expSmoothingSeries($series, $alpha, $horizon);

        // Fix: assign array_keys to a variable before passing to end()
        $keys = array_keys($series);
        $lastDate = Carbon::parse(end($keys));

        $forecastDates = [];
        for ($i = 1; $i <= $horizon; $i++) {
            $forecastDates[] = $lastDate->copy()->addDays($i)->format('Y-m-d');
        }

        return ['dates' => $forecastDates, 'values' => $forecastValues];
    }

    public function expSmoothingSeries(array $history, float $alpha = 0.5, int $horizon = 7): array
    {
        $values = array_values($history);
        if (empty($values)) return array_fill(0, $horizon, 0.0);

        $s = $values[0]; // initial level
        foreach ($values as $x) {
            $s = $alpha * $x + (1 - $alpha) * $s;
        }

        $forecast = [];
        for ($h = 1; $h <= $horizon; $h++) {
            // basic exponential smoothing projection
            $forecast[] = $s;
            // optionally include trend using simple difference
            $s = $s; // keep it constant; can replace with Holt's method later
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
        $prices = ProductPrices::with('product')->get();

        foreach ($prices as $price) {
            // get product-level daily sales
            $history = $this->dailyHistory($price->product_id, $historyDays);

            $forecast = $this->expSmoothingSeries($history, $alpha, $horizon);
            $avgDaily = array_sum($forecast) / count($forecast);

            if ($avgDaily <= 0) continue;

            $daysLeft = $price->quantity_stock / $avgDaily;

            if ($daysLeft <= $thresholdDays) {
                $results[] = [
                    'product_id' => $price->product_id,
                    'product' => $price->product->name,
                    'size' => $price->size,
                    'stock' => $price->quantity_stock,
                    'forecast_daily' => round($avgDaily, 2),
                    'days_left' => round($daysLeft, 2),
                ];
            }
        }

        usort($results, fn($a, $b) => $a['days_left'] <=> $b['days_left']);
        return $results;
    }
}
