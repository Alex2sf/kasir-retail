<x-app-layout title="Kelola Tenant">
    <x-slot:sidebar>
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 px-3">Menu Utama</p>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"/></svg>
            Dashboard
        </a>
        <a href="{{ route('admin.tenants.index') }}" class="sidebar-link active">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
            Kelola Tenant
        </a>
    </x-slot:sidebar>

    <x-slot:header>Kelola Tenant</x-slot:header>
    <x-slot:subtitle>Kelola semua toko UMKM</x-slot:subtitle>

    <div class="glass-card p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari toko..." class="form-input flex-1 min-w-[200px]">
            <select name="status" class="form-input w-auto">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status')==='active'?'selected':'' }}>Aktif</option>
                <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Nonaktif</option>
            </select>
            <button type="submit" class="btn-primary">Filter</button>
        </form>
    </div>

    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="modern-table">
                <thead><tr><th>Toko</th><th>Produk</th><th>Transaksi</th><th>Omzet</th><th>Paket</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($tenants as $tenant)
                    <tr>
                        <td><div class="flex items-center gap-3"><div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center text-white font-bold text-sm">{{ strtoupper(substr($tenant->name,0,2)) }}</div><div><p class="font-semibold">{{ $tenant->name }}</p><p class="text-xs text-slate-400">{{ $tenant->slug }}</p></div></div></td>
                        <td>{{ $tenant->products_count }}</td>
                        <td>{{ $tenant->transactions_count }}</td>
                        <td class="font-semibold">Rp {{ number_format($tenant->total_revenue??0,0,',','.') }}</td>
                        <td><span class="badge badge-info">{{ ucfirst($tenant->plan) }}</span></td>
                        <td><span class="badge {{ $tenant->status==='active'?'badge-success':'badge-danger' }}">{{ ucfirst($tenant->status) }}</span></td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.tenants.show',$tenant) }}" class="btn-secondary text-xs px-2 py-1">Detail</a>
                                <form method="POST" action="{{ route('admin.tenants.toggle-status',$tenant) }}">@csrf @method('PATCH')
                                    <button class="text-xs px-2 py-1 {{ $tenant->status==='active'?'btn-danger':'btn-success' }}">{{ $tenant->status==='active'?'Nonaktifkan':'Aktifkan' }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-8 text-slate-400">Belum ada tenant</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $tenants->links() }}</div>
    </div>
</x-app-layout>
