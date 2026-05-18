<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('status', 'active')->count();
        $totalOwners = User::where('role', 'owner')->count();
        $totalRevenue = Transaction::where('status', 'completed')->sum('total');
        $todayRevenue = Transaction::where('status', 'completed')->whereDate('created_at', today())->sum('total');
        $todayTransactions = Transaction::where('status', 'completed')->whereDate('created_at', today())->count();

        // Monthly revenue for chart (last 6 months)
        $monthlyRevenue = Transaction::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Top tenants by revenue
        $topTenants = Tenant::withCount('transactions')
            ->withSum(['transactions as total_revenue' => function ($q) {
                $q->where('status', 'completed');
            }], 'total')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        // Recent tenants
        $recentTenants = Tenant::with('users')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalTenants', 'activeTenants', 'totalOwners', 'totalRevenue',
            'todayRevenue', 'todayTransactions', 'monthlyRevenue',
            'topTenants', 'recentTenants'
        ));
    }
}
