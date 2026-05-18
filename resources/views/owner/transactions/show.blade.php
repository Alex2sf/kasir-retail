<x-app-layout title="Detail Transaksi">
    <x-slot:sidebar>@include('owner.partials.sidebar')</x-slot:sidebar>
    <x-slot:header>{{ $transaction->invoice_number }}</x-slot:header>
    <x-slot:subtitle>Detail transaksi</x-slot:subtitle>
    <x-slot:headerActions>
        <a href="{{ route('owner.pos.receipt', $transaction) }}" target="_blank" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Struk
        </a>
    </x-slot:headerActions>

    <div class="max-w-3xl grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Transaction Info --}}
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold mb-4">📋 Informasi</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-slate-400">Invoice</dt><dd class="font-mono font-semibold">{{ $transaction->invoice_number }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Waktu</dt><dd>{{ $transaction->created_at->format('d M Y H:i:s') }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Kasir</dt><dd>{{ $transaction->user->name }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Pelanggan</dt><dd>{{ $transaction->customer->name ?? 'Umum' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Metode</dt><dd><span class="badge badge-info">{{ strtoupper($transaction->payment_method) }}</span></dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Status</dt><dd><span class="badge badge-success">{{ ucfirst($transaction->status) }}</span></dd></div>
            </dl>
        </div>

        {{-- Payment Summary --}}
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold mb-4">💰 Ringkasan</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-slate-400">Subtotal</dt><dd>Rp {{ number_format($transaction->subtotal,0,',','.') }}</dd></div>
                @if($transaction->discount_amount > 0)
                <div class="flex justify-between"><dt class="text-slate-400">Diskon</dt><dd class="text-red-400">- Rp {{ number_format($transaction->discount_amount,0,',','.') }}</dd></div>
                @endif
                @if($transaction->tax_amount > 0)
                <div class="flex justify-between"><dt class="text-slate-400">Pajak</dt><dd>Rp {{ number_format($transaction->tax_amount,0,',','.') }}</dd></div>
                @endif
                <div class="flex justify-between border-t border-slate-200 dark:border-slate-700 pt-3"><dt class="font-bold text-base">Total</dt><dd class="font-bold text-base text-indigo-500">Rp {{ number_format($transaction->total,0,',','.') }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Dibayar</dt><dd>Rp {{ number_format($transaction->paid_amount,0,',','.') }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Kembalian</dt><dd>Rp {{ number_format($transaction->change_amount,0,',','.') }}</dd></div>
            </dl>
        </div>

        {{-- Items --}}
        <div class="lg:col-span-2 glass-card overflow-hidden">
            <div class="p-4 border-b border-slate-200 dark:border-slate-700"><h3 class="text-lg font-bold">🛒 Item Transaksi</h3></div>
            <table class="modern-table">
                <thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Diskon</th><th>Subtotal</th></tr></thead>
                <tbody>
                    @foreach($transaction->items as $item)
                    <tr>
                        <td class="font-semibold">{{ $item->product_name }}</td>
                        <td>Rp {{ number_format($item->price,0,',','.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->discount,0,',','.') }}</td>
                        <td class="font-semibold">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
