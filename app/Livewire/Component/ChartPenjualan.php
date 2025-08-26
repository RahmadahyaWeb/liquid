<?php

namespace App\Livewire\Component;

use App\Models\SalesInvoice;
use Carbon\Carbon;
use Livewire\Component;

class ChartPenjualan extends Component
{
    public $range = '7';
    public $thisWeekTotal;
    public $lastWeekTotal;
    public $growthPercentage;
    public $chartData = [];

    public function mount()
    {
        $this->loadChart();
        $this->fetchSalesData();
    }

    public function updatedRange()
    {
        $this->fetchSalesData();
        $this->loadChart();
    }

    public function loadChart()
    {
        $today = Carbon::today();

        if ($this->range === '1') {
            $startDate = $today;
            $endDate = $today;
            $days = 1;
        } elseif ($this->range === '30') {
            $startDate = $today->copy()->subDays(29);
            $endDate = $today;
            $days = 30;
        } else {
            $startDate = $today->copy()->subDays(6);
            $endDate = $today;
            $days = 7;
        }

        $sales = SalesInvoice::selectRaw('tanggal, SUM(total_bayar) as total')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $categories = [];
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $categories[] = Carbon::parse($date)->translatedFormat('d F');
            $found = $sales->firstWhere('tanggal', $date);
            $data[] = $found ? (float) $found->total : 0;
        }

        $this->chartData = [
            'categories' => $categories,
            'series' => [
                [
                    'name' => 'Total Penjualan',
                    'data' => $data,
                ],
            ],
        ];
    }

    public function fetchSalesData()
    {
        $today = Carbon::today();

        // Tentukan range saat ini
        $range = (int) $this->range;

        $currentStart = $today->copy()->subDays($range - 1);
        $currentEnd = $today;

        $previousStart = $currentStart->copy()->subDays($range);
        $previousEnd = $currentStart->copy()->subDay();

        $this->thisWeekTotal = SalesInvoice::whereBetween('tanggal', [$currentStart, $currentEnd])
            ->sum('total_bayar');

        $this->lastWeekTotal = SalesInvoice::whereBetween('tanggal', [$previousStart, $previousEnd])
            ->sum('total_bayar');

        $this->growthPercentage = $this->calculateGrowthPercentage($this->thisWeekTotal, $this->lastWeekTotal);
    }

    private function calculateGrowthPercentage($thisWeek, $lastWeek)
    {
        if ($lastWeek > 0) {
            return round((($thisWeek - $lastWeek) / $lastWeek) * 100, 2);
        } elseif ($thisWeek > 0) {
            return 100;
        }

        return 0;
    }

    public function render()
    {
        return view('livewire.component.chart-penjualan');
    }
}
