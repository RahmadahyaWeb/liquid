<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function loadPenjualanChart(Request $request)
    {
        $today = Carbon::today();

        if ($request->range === '1') {
            $startDate = $today;
            $endDate = $today;
            $days = 1;
        } elseif ($request->range === '30') {
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

        return response()->json([
            'categories' => $categories,
            'series' => [
                [
                    'name' => 'Total Penjualan',
                    'data' => $data
                ]
            ]
        ]);
    }
}
