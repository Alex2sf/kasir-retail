<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::withCount(['users', 'products', 'transactions'])
            ->withSum(['transactions as total_revenue' => function ($q) {
                $q->where('status', 'completed');
            }], 'total');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tenants = $query->latest()->paginate(15);

        return view('admin.tenants.index', compact('tenants'));
    }

    public function show(Tenant $tenant)
    {
        $tenant->loadCount(['users', 'products', 'transactions', 'customers']);
        $tenant->load(['users' => fn($q) => $q->where('role', 'owner')]);

        $recentTransactions = $tenant->transactions()
            ->with('user')
            ->where('status', 'completed')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.tenants.show', compact('tenant', 'recentTransactions'));
    }

    public function toggleStatus(Tenant $tenant)
    {
        $tenant->status = $tenant->status === 'active' ? 'inactive' : 'active';
        $tenant->save();

        $statusText = $tenant->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Tenant {$tenant->name} berhasil {$statusText}.");
    }

    public function updatePlan(Request $request, Tenant $tenant)
    {
        $request->validate([
            'plan' => 'required|in:free,basic,premium',
        ]);

        $tenant->update(['plan' => $request->plan]);

        return back()->with('success', "Paket tenant {$tenant->name} berhasil diubah ke {$request->plan}.");
    }
}
