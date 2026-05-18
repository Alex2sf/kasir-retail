<x-app-layout title="Admin Dashboard">
    <x-slot:sidebar>
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 px-3">Menu Utama</p>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link active">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
            Dashboard
        </a>
        <a href="{{ route('admin.tenants.index') }}" class="sidebar-link">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Kelola Tenant
        </a>
    </x-slot:sidebar>

    <x-slot:header>Dashboard Admin</x-slot:header>
    <x-slot:subtitle>Pantau seluruh UMKM dari satu tempat</x-slot:subtitle>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <x-stat-card
            title="Total Tenant"
            :value="number_format($totalTenants)"
            from="#6366f1" to="#8b5cf6"
            icon='<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>'
        />
        <x-stat-card
            title="Tenant Aktif"
            :value="number_format($activeTenants)"
            from="#10b981" to="#059669"
            icon='<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        />
        <x-stat-card
            title="Transaksi Hari Ini"
            :value="number_format($todayTransactions)"
            from="#06b6d4" to="#0891b2"
            icon='<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>'
        />
        <x-stat-card
            title="Omzet Global"
            :value="'Rp ' . number_format($totalRevenue, 0, ',', '.')"
            from="#f59e0b" to="#d97706"
            icon='<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Chart --}}
        <div class="lg:col-span-2 glass-card p-6">
            <h3 class="text-lg font-bold mb-4">📊 Grafik Transaksi Global (6 Bulan)</h3>
            <div class="h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Top Tenants --}}
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold mb-4">🏆 Tenant Paling Aktif</h3>
            <div class="space-y-4">
                @forelse($topTenants as $i => $tenant)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold text-white
                            {{ $i === 0 ? 'bg-yellow-500' : ($i === 1 ? 'bg-slate-400' : 'bg-amber-700') }}
                            {{ $i > 2 ? '!bg-slate-300 !text-slate-600' : '' }}">
                            {{ $i + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold truncate">{{ $tenant->name }}</p>
                            <p class="text-xs text-slate-400">{{ number_format($tenant->transactions_count) }} transaksi</p>
                        </div>
                        <span class="text-sm font-bold text-indigo-500">Rp {{ number_format($tenant->total_revenue ?? 0, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <div class="empty-state py-8">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                        <p class="text-slate-400 text-sm">Belum ada data tenant</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Tenants --}}
    <div class="glass-card p-6 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold">🆕 UMKM Terbaru</h3>
            <a href="{{ route('admin.tenants.index') }}" class="btn-secondary text-xs">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Nama Toko</th>
                        <th>Owner</th>
                        <th>Paket</th>
                        <th>Status</th>
                        <th>Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTenants as $tenant)
                        <tr>
                            <td class="font-semibold">{{ $tenant->name }}</td>
                            <td>{{ $tenant->users->first()?->name ?? '-' }}</td>
                            <td><span class="badge badge-info">{{ ucfirst($tenant->plan) }}</span></td>
                            <td>
                                <span class="badge {{ $tenant->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                                    {{ $tenant->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-slate-400 text-sm">{{ $tenant->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-400 py-8">Belum ada tenant terdaftar</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart');
            if (ctx) {
                const monthlyData = @json($monthlyRevenue);
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: monthlyData.map(d => months[d.month - 1] + ' ' + d.year),
                        datasets: [{
                            label: 'Omzet',
                            data: monthlyData.map(d => d.revenue),
                            backgroundColor: 'rgba(99, 102, 241, 0.2)',
                            borderColor: '#6366f1',
                            borderWidth: 2,
                            borderRadius: 8,
                            barPercentage: 0.6,
                        }, {
                            label: 'Transaksi',
                            data: monthlyData.map(d => d.count),
                            type: 'line',
                            borderColor: '#06b6d4',
                            backgroundColor: 'rgba(6, 182, 212, 0.1)',
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y1',
                            pointRadius: 4,
                            pointBackgroundColor: '#06b6d4',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top', labels: { usePointStyle: true, padding: 15 } },
                        },
                        scales: {
                            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                            y1: { position: 'right', beginAtZero: true, grid: { display: false } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
