<x-app-layout title="Dashboard">
    <x-slot:sidebar>@include('owner.partials.sidebar')</x-slot:sidebar>
    <x-slot:header>Dashboard</x-slot:header>
    <x-slot:subtitle>{{ $currentTenant->name ?? 'Toko Anda' }}</x-slot:subtitle>
    <x-slot:headerActions>
        <a id="tour-pos-btn" href="{{ route('owner.pos.index') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            Buka Kasir
        </a>
    </x-slot:headerActions>

    {{-- Stat Cards --}}
    <div id="tour-stats" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <x-stat-card title="Omzet Hari Ini" :value="'Rp '.number_format($todayRevenue,0,',','.')" from="#6366f1" to="#8b5cf6" icon='<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1"/></svg>'/>
        <x-stat-card title="Transaksi Hari Ini" :value="number_format($todayTransactions)" from="#06b6d4" to="#0891b2" icon='<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>'/>
        <x-stat-card title="Omzet Bulan Ini" :value="'Rp '.number_format($monthRevenue,0,',','.')" from="#10b981" to="#059669" icon='<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>'/>
        <x-stat-card title="Total Produk" :value="number_format($totalProducts)" from="#f59e0b" to="#d97706" icon='<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4"/></svg>'/>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Chart --}}
        <div id="tour-chart" class="lg:col-span-2 glass-card p-6">
            <h3 class="text-lg font-bold mb-4">📈 Penjualan 7 Hari Terakhir</h3>
            <div class="h-64"><canvas id="salesChart"></canvas></div>
        </div>

        {{-- Top Products --}}
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold mb-4">🏆 Produk Terlaris</h3>
            <div class="space-y-3">
                @forelse($topProducts as $i => $p)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg gradient-primary flex items-center justify-center text-white font-bold text-xs">{{ $i+1 }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate">{{ $p->product_name }}</p>
                        <p class="text-xs text-slate-400">{{ number_format($p->total_sold) }} terjual</p>
                    </div>
                    <span class="text-sm font-bold text-green-500">Rp {{ number_format($p->total_revenue,0,',','.') }}</span>
                </div>
                @empty
                <p class="text-center text-slate-400 py-4 text-sm">Belum ada data penjualan</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Low Stock Alert --}}
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold mb-4">⚠️ Stok Menipis</h3>
            @forelse($lowStockProducts as $p)
            <div class="flex items-center justify-between p-3 mb-2 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                <div>
                    <p class="text-sm font-semibold">{{ $p->name }}</p>
                    <p class="text-xs text-slate-400">Min: {{ $p->min_stock }} {{ $p->unit }}</p>
                </div>
                <span class="badge badge-danger">{{ $p->stock }} {{ $p->unit }}</span>
            </div>
            @empty
            <p class="text-center text-slate-400 py-4 text-sm">Semua stok aman 👍</p>
            @endforelse
        </div>

        {{-- Recent Transactions --}}
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold mb-4">🕐 Transaksi Terakhir</h3>
            @forelse($recentTransactions as $trx)
            <div class="flex items-center justify-between p-3 mb-2 rounded-xl bg-slate-50 dark:bg-slate-700/50">
                <div>
                    <p class="text-sm font-semibold">{{ $trx->invoice_number }}</p>
                    <p class="text-xs text-slate-400">{{ $trx->created_at->diffForHumans() }}</p>
                </div>
                <span class="font-bold text-indigo-500">Rp {{ number_format($trx->total,0,',','.') }}</span>
            </div>
            @empty
            <p class="text-center text-slate-400 py-4 text-sm">Belum ada transaksi</p>
            @endforelse
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart');
        if (!ctx) return;
        const data = @json($weeklyRevenue);
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(d => new Date(d.date).toLocaleDateString('id-ID', {weekday:'short',day:'numeric'})),
                datasets: [{
                    label: 'Omzet',
                    data: data.map(d => d.revenue),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,0.1)',
                    fill: true, tension: 0.4, pointRadius: 5, pointBackgroundColor: '#6366f1',
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, grid: {color:'rgba(0,0,0,0.05)'} }, x: { grid: {display:false} } }
            }
        });
    });
    </script>

    @if(session('show_onboarding_tour'))
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            if (typeof window.driver !== 'undefined') {
                const driverObj = window.driver.js.driver({
                    showProgress: true,
                    steps: [
                        { popover: { title: 'Selamat Datang!', description: 'Toko Anda berhasil didaftarkan. Mari kita jelajahi fitur-fitur yang ada.', align: 'center' }},
                        { element: '#menu-pos', popover: { title: 'Kasir / POS', description: 'Gunakan menu ini untuk melayani pembeli dan mencatat pesanan.', side: "right", align: 'start' }},
                        { element: '#menu-produk', popover: { title: 'Kelola Produk', description: 'Kelola daftar barang dagangan, stok, dan harga jual di sini.', side: "right", align: 'start' }},
                        { element: '#menu-kategori', popover: { title: 'Kategori Produk', description: 'Kelompokkan barang dagangan agar lebih rapi saat di kasir.', side: "right", align: 'start' }},
                        { element: '#menu-transaksi', popover: { title: 'Riwayat Transaksi', description: 'Semua riwayat penjualan akan tercatat otomatis di menu ini.', side: "right", align: 'start' }},
                        { element: '#menu-pengaturan', popover: { title: 'Pengaturan Toko', description: 'Atur nama toko, logo, pajak, dan metode pembayaran di sini.', side: "right", align: 'start' }},
                        { element: '#tour-stats', popover: { title: 'Ringkasan Bisnis', description: 'Pantau omzet dan transaksi harian toko Anda secara real-time di sini.', side: "bottom", align: 'center' }}
                    ],
                    nextBtnText: 'Lanjut',
                    prevBtnText: 'Kembali',
                    doneBtnText: 'Selesai',
                });
                driverObj.drive();
            }
        }, 500);
    });
    </script>
    @endif
</x-app-layout>
