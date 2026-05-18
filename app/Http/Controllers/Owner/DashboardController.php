<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        $todayRevenue = Transaction::where('tenant_id', $tenantId)
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total');

        $todayTransactions = Transaction::where('tenant_id', $tenantId)
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->count();

        $monthRevenue = Transaction::where('tenant_id', $tenantId)
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $totalProducts = Product::where('tenant_id', $tenantId)->where('is_active', true)->count();

        // Low stock products
        $lowStockProducts = Product::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->where('track_stock', true)
            ->whereColumn('stock', '<=', 'min_stock')
            ->take(5)
            ->get();

        // Top selling products (this month)
        $topProducts = DB::table('transaction_items')
            ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->where('transactions.tenant_id', $tenantId)
            ->where('transactions.status', 'completed')
            ->whereMonth('transactions.created_at', now()->month)
            ->select('transaction_items.product_name', DB::raw('SUM(transaction_items.quantity) as total_sold'), DB::raw('SUM(transaction_items.subtotal) as total_revenue'))
            ->groupBy('transaction_items.product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Weekly revenue for chart (last 7 days)
        $weeklyRevenue = Transaction::where('tenant_id', $tenantId)
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Recent transactions
        $recentTransactions = Transaction::where('tenant_id', $tenantId)
            ->with('user', 'customer')
            ->latest()
            ->take(5)
            ->get();

        return view('owner.dashboard', compact(
            'todayRevenue', 'todayTransactions', 'monthRevenue', 'totalProducts',
            'lowStockProducts', 'topProducts', 'weeklyRevenue', 'recentTransactions'
        ));
    }
}
