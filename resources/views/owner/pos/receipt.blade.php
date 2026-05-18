<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk - {{ $transaction->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 12px; width: 80mm; margin: 0 auto; padding: 8px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #333; margin: 6px 0; }
        .flex { display: flex; justify-content: space-between; }
        .store-name { font-size: 16px; font-weight: bold; margin-bottom: 2px; }
        .item-row { margin: 2px 0; }
        .total-row { font-size: 14px; font-weight: bold; }
        .footer { margin-top: 10px; font-size: 10px; text-align: center; color: #666; }
        @media screen { body { max-width: 320px; border: 1px solid #ddd; padding: 16px; margin: 20px auto; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); } }
        @media print { @page { size: 80mm auto; margin: 0; } }
    </style>
</head>
<body class="receipt-area">
    <div class="text-center">
        <div class="store-name">{{ $transaction->tenant->name ?? 'KASIR RETAIL' }}</div>
        <div>{{ $transaction->tenant->address ?? '' }}</div>
        <div>{{ $transaction->tenant->phone ?? '' }}</div>
    </div>

    <div class="divider"></div>

    <div class="flex"><span>No: {{ $transaction->invoice_number }}</span></div>
    <div class="flex"><span>Tanggal: {{ $transaction->created_at->format('d/m/Y H:i') }}</span></div>
    <div class="flex"><span>Kasir: {{ $transaction->user->name }}</span></div>
    @if($transaction->customer)
    <div class="flex"><span>Pelanggan: {{ $transaction->customer->name }}</span></div>
    @endif

    <div class="divider"></div>

    @foreach($transaction->items as $item)
    <div class="item-row">
        <div>{{ $item->product_name }}</div>
        <div class="flex">
            <span>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</span>
            <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
    </div>
    @endforeach

    <div class="divider"></div>

    <div class="flex"><span>Subtotal</span><span>{{ number_format($transaction->subtotal, 0, ',', '.') }}</span></div>
    @if($transaction->discount_amount > 0)
    <div class="flex"><span>Diskon</span><span>-{{ number_format($transaction->discount_amount, 0, ',', '.') }}</span></div>
    @endif
    @if($transaction->tax_amount > 0)
    <div class="flex"><span>Pajak</span><span>{{ number_format($transaction->tax_amount, 0, ',', '.') }}</span></div>
    @endif

    <div class="divider"></div>

    <div class="flex total-row"><span>TOTAL</span><span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span></div>
    <div class="flex"><span>Bayar ({{ strtoupper($transaction->payment_method) }})</span><span>{{ number_format($transaction->paid_amount, 0, ',', '.') }}</span></div>
    <div class="flex bold"><span>Kembali</span><span>{{ number_format($transaction->change_amount, 0, ',', '.') }}</span></div>

    <div class="divider"></div>

    <div class="footer">
        <p>Terima kasih telah berbelanja!</p>
        <p>{{ $transaction->tenant->name ?? 'KasirRetail' }}</p>
        <p>Powered by KasirRetail POS</p>
    </div>

    <div class="no-print" style="text-align:center; margin-top:20px;">
        <button onclick="window.print()" style="padding:10px 30px; background:#6366f1; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:bold;">🖨️ Cetak Struk</button>
        <button onclick="window.close()" style="padding:10px 30px; background:#e2e8f0; color:#475569; border:none; border-radius:8px; cursor:pointer; font-weight:bold; margin-left:8px;">Tutup</button>
    </div>

    <script>
        // Auto print
        window.addEventListener('load', () => {
            // Delay to ensure rendering
            setTimeout(() => { /* window.print(); */ }, 500);
        });
    </script>
</body>
</html>
