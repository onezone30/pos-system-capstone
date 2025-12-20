<?php

namespace App\Livewire\Sales;

use App\Models\Order;
use App\Models\SalesHistory;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class SalesList extends Component
{
    use WithPagination;

    public $filterRange = 'today';
    public $order = 'asc';
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->setDateRange('today');
    }

    #[On('rangeChanged')]
    public function filterByRange($range)
    {
        $this->filterRange = $range;
        $this->setDateRange($range);
        $this->resetPage();
    }

    #[On('orderChanged')]
    public function changeOrder($order)
    {
        $this->order = $order;
        $this->resetPage();
    }

    private function setDateRange($range)
    {
        $this->endDate = now();
        switch ($range) {
            case '3':
                $this->startDate = now()->subDays(3);
                break;
            case '7':
                $this->startDate = now()->subDays(7);
                break;
            case '14':
                $this->startDate = now()->subDays(14);
                break;
            case '30':
                $this->startDate = now()->subDays(30);
                break;
            case '60':
                $this->startDate = now()->subDays(60);
                break;
            case 'all':
                $this->startDate = Carbon::create(2020, 1, 1);
                break;
            default:
                $this->startDate = now()->startOfDay();
                break;
        }
    }

    public function render()
    {
        $sales = Order::with(['items.product', 'user'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('amount_paid')
            ->orderBy('created_at', $this->order)
            ->paginate(10);


        $totalSales = SalesHistory::whereBetween('date', [
                $this->startDate->toDateString(),
                $this->endDate->toDateString()
            ])->sum('total_sales');

        $totalOrders = Order::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('amount_paid')
            ->count();

        $averageOrder = Order::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('total_amount')
            ->avg('total_amount');

        $top_payment_method = Order::selectRaw('payment_method, COUNT(*) as method_count')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('amount_paid')
            ->groupBy('payment_method')
            ->orderByDesc('method_count')
            ->value('payment_method');

        return view('livewire.sales.sales-list', compact(
            'sales',
            'totalSales',
            'totalOrders',
            'averageOrder',
            'top_payment_method'
        ));
    }
}
