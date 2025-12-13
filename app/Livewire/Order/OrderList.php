<?php

namespace App\Livewire\Order;

use App\Models\Order;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;

    public $filterRange = 'today';
    public $order = 'asc';
    public $startDate;
    public $user = '';
    public $endDate;

    public function mount()
    {
        $this->setDateRange($this->filterRange);
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

    #[On('userChanged')]
    public function filterByUser($userId)
    {
        $this->user = $userId;
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
                $this->startDate = now()->startOfYear(); // or a very old date
                break;
            default:
                $this->startDate = now()->startOfDay();
                break;
        }
    }

    #[On(['editOrder', 'deleteOrder'])]
    public function render()
    {
        $query = Order::with(['items.product', 'user'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('amount_paid');

        if (auth()->user()->role === 'cashier') {
            $query->where('user_id', auth()->id());
        } else {
            if ($this->user ) {
                $query->where('user_id', $this->user);
            }
        }

        $orders = $query->orderBy('created_at', $this->order)
            ->paginate(10);

        return view('livewire.order.order-list', [
            'orders' => $orders,
        ]);
    }
}
