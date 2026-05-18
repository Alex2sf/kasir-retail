<x-app-layout title="Transaksi">
    <x-slot:sidebar>@include('owner.partials.sidebar')</x-slot:sidebar>
    <x-slot:header>Riwayat Transaksi</x-slot:header>
    <x-slot:subtitle>Semua transaksi toko Anda</x-slot:subtitle>

    {{-- Filters --}}
    <div class="glass-card p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. invoice..." class="form-input flex-1 min-w-[180px]">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input w-auto">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input w-auto">
            <select name="payment_method" class="form-input w-auto">
                <option value="">Semua Metode</option>
                <option value="cash" {{ request('payment_method')==='cash'?'selected':'' }}>Cash</option>
                <option value="qris" {{ request('payment_method')==='qris'?'selected':'' }}>QRIS</option>
                <option value="transfer" {{ request('payment_method')==='transfer'?'selected':'' }}>Transfer</option>
                <option value="debit" {{ request('payment_method')==='debit'?'selected':'' }}>Debit</option>
            </select>
            <button type="submit" class="btn-primary">Filter</button>
        </form>
    </div>

    {{-- Transactions Table --}}
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="modern-table">
                <thead><tr><th>Invoice</th><th>Waktu</th><th>Kasir</th><th>Pelanggan</th><th>Metode</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr>
                        <td class="font-mono text-sm font-semibold">{{ $trx->invoice_number }}</td>
                        <td class="text-sm">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $trx->user->name }}</td>
                        <td>{{ $trx->customer->name ?? 'Umum' }}</td>
                        <td><span class="badge badge-info">{{ strtoupper($trx->payment_method) }}</span></td>
                        <td class="font-bold">Rp {{ number_format($trx->total,0,',','.') }}</td>
                        <td><span class="badge {{ $trx->status==='completed'?'badge-success':($trx->status==='cancelled'?'badge-danger':'badge-warning') }}">{{ ucfirst($trx->status) }}</span></td>
                        <td>
                            <a href="{{ route('owner.transactions.show', $trx) }}" class="btn-secondary text-xs px-2 py-1">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8"><div class="empty-state py-8"><p class="text-slate-400">Belum ada transaksi</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-700">{{ $transactions->links() }}</div>
        @endif
    </div>
</x-app-layout>
