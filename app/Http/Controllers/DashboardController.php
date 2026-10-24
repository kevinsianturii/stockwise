<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total active products
        $totalProducts = Product::active()->count();

        // Total units in stock (sum of all current_stock)
        $totalUnits = Product::active()->sum('current_stock');

        // Products below minimum stock (includes out-of-stock)
        $lowStockCount = Product::active()
            ->where('current_stock', '<=', DB::raw('minimum_stock'))
            ->count();

        // Out-of-stock count
        $outOfStockCount = Product::active()
            ->where('current_stock', 0)
            ->count();

        // Stock movements this month
        $movementsThisMonth = StockMovement::whereMonth('occurred_at', now()->month)
            ->whereYear('occurred_at', now()->year)
            ->count();

        // Recent movements (last 10)
        $recentMovements = StockMovement::with(['product', 'user'])
            ->orderByDesc('occurred_at')
            ->limit(10)
            ->get();

        // Products needing restock (below minimum, sorted by urgency)
        $lowStockProducts = Product::active()
            ->with('category')
            ->where('current_stock', '<=', DB::raw('minimum_stock'))
            ->orderBy('current_stock')
            ->limit(8)
            ->get();

        // Chart data: daily movements for last 30 days
        $chartData = $this->getChartData();

        return view('dashboard', compact(
            'totalProducts',
            'totalUnits',
            'lowStockCount',
            'outOfStockCount',
            'movementsThisMonth',
            'recentMovements',
            'lowStockProducts',
            'chartData'
        ));
    }

    /**
     * Build Chart.js-compatible data for stock movements over last 30 days.
     */
    private function getChartData(): array
    {
        $days = collect();
        for ($i = 29; $i >= 0; $i--) {
            $days->push(now()->subDays($i)->format('Y-m-d'));
        }

        // Get actual movement data grouped by date and type
        $movements = StockMovement::select(
                DB::raw('DATE(occurred_at) as date'),
                'type',
                DB::raw('SUM(quantity) as total')
            )
            ->where('occurred_at', '>=', now()->subDays(29)->startOfDay())
            ->groupBy('date', 'type')
            ->get()
            ->groupBy('date');

        $labels = [];
        $inData = [];
        $outData = [];

        foreach ($days as $day) {
            $labels[] = \Carbon\Carbon::parse($day)->locale('id')->isoFormat('D MMM');

            $dayMovements = $movements->get($day, collect());
            $inData[] = (int) $dayMovements->where('type', 'in')->sum('total');
            $outData[] = (int) $dayMovements->where('type', 'out')->sum('total');
        }

        return compact('labels', 'inData', 'outData');
    }
}
