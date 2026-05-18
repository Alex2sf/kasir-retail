<x-app-layout title="Detail Tenant">
    <x-slot:sidebar>
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 px-3">Menu Utama</p>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/></svg> Dashboard</a>
        <a href="{{ route('admin.tenants.index') }}" class="sidebar-link active"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg> Kelola Tenant</a>
    </x-slot:sidebar>
    <x-slot:header>{{ $tenant->name }}</x-slot:header>
    <x-slot:subtitle>Detail informasi tenant</x-slot:subtitle>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-5 mb-6">
        <x-stat-card title="Produk" :value="number_format($tenant->products_count)" from="#6366f1" to="#8b5cf6" icon='<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>'/>
        <x-stat-card title="Transaksi" :value="number_format($tenant->transactions_count)" from="#06b6d4" to="#0891b2" icon='<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>'/>
        <x-stat-card title="Pelanggan" :value="number_format($tenant->customers_count)" from="#f59e0b" to="#d97706" icon='<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'/>
        <x-stat-card title="Users" :value="number_format($tenant->users_count)" from="#10b981" to="#059669" icon='<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'/>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold mb-4">📋 Informasi Toko</h3>
            <dl class="space-y-3">
                <div class="flex justify-between"><dt class="text-slate-400">Nama</dt><dd class="font-semibold">{{ $tenant->name }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Slug</dt><dd>{{ $tenant->slug }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Email</dt><dd>{{ $tenant->email ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Telepon</dt><dd>{{ $tenant->phone ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Paket</dt><dd><span class="badge badge-info">{{ ucfirst($tenant->plan) }}</span></dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Status</dt><dd><span class="badge {{ $tenant->status==='active'?'badge-success':'badge-danger' }}">{{ ucfirst($tenant->status) }}</span></dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Terdaftar</dt><dd>{{ $tenant->created_at->format('d M Y H:i') }}</dd></div>
            </dl>
        </div>

        <div class="glass-card p-6">
            <h3 class="text-lg font-bold mb-4">🕐 Transaksi Terakhir</h3>
            <div class="space-y-3">
                @forelse($recentTransactions as $trx)
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-700/50">
                    <div>
                        <p class="text-sm font-semibold">{{ $trx->invoice_number }}</p>
                        <p class="text-xs text-slate-400">{{ $trx->created_at->diffForHumans() }} · {{ $trx->user->name }}</p>
                    </div>
                    <span class="font-bold text-indigo-500">Rp {{ number_format($trx->total,0,',','.') }}</span>
                </div>
                @empty
                <p class="text-center text-slate-400 py-4">Belum ada transaksi</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
