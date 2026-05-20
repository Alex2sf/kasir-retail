<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    private function tenantId(): int
    {
        return Auth::user()->tenant_id;
    }

    public function index(Request $request)
    {
        $query = Transaction::where('tenant_id', $this->tenantId())
            ->with('user', 'customer');

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $transactions = $query->latest()->paginate(20);

        return view('owner.transactions.index', compact('transactions'));
    }

    public function export(Request $request)
    {
        $query = Transaction::where('tenant_id', $this->tenantId())
            ->with(['user', 'customer', 'items']);

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $transactions = $query->latest()->get();

        $fileName = 'laporan_transaksi_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'No. Invoice',
            'Tanggal & Waktu',
            'Kasir',
            'Pelanggan',
            'Daftar Produk',
            'Subtotal',
            'Diskon',
            'Pajak',
            'Total',
            'Dibayar',
            'Kembalian',
            'Metode Pembayaran',
            'Status',
            'Catatan'
        ];

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper excel encoding of special characters
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns);

            foreach ($transactions as $trx) {
                $productDetails = [];
                foreach ($trx->items as $item) {
                    $productDetails[] = $item->product_name . ' (x' . $item->quantity . ')';
                }
                $productList = implode(', ', $productDetails);

                fputcsv($file, [
                    $trx->invoice_number,
                    $trx->created_at->format('Y-m-d H:i:s'),
                    $trx->user->name,
                    $trx->customer->name ?? 'Umum',
                    $productList,
                    $trx->subtotal,
                    $trx->discount_amount,
                    $trx->tax_amount,
                    $trx->total,
                    $trx->paid_amount,
                    $trx->change_amount,
                    strtoupper($trx->payment_method),
                    ucfirst($trx->status),
                    $trx->notes
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show(Transaction $transaction)
    {
        abort_if($transaction->tenant_id !== $this->tenantId(), 403);
        $transaction->load('items.product', 'customer', 'user');

        return view('owner.transactions.show', compact('transaction'));
    }
}
