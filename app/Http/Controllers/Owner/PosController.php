<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function __construct(
        private TransactionService $transactionService
    ) {}

    private function tenantId(): int
    {
        return Auth::user()->tenant_id;
    }

    public function index()
    {
        $categories = Category::where('tenant_id', $this->tenantId())
            ->where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        $products = Product::where('tenant_id', $this->tenantId())
            ->where('is_active', true)
            ->with('category')
            ->get();

        $customers = Customer::where('tenant_id', $this->tenantId())
            ->orderBy('name')
            ->get();

        return view('owner.pos.index', compact('categories', 'products', 'customers'));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');

        $products = Product::where('tenant_id', $this->tenantId())
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('barcode', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->with('category')
            ->take(20)
            ->get();

        return response()->json($products);
    }

    public function processTransaction(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,debit,credit,qris,transfer',
            'customer_id' => 'nullable|exists:customers,id',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed',
            'notes' => 'nullable|string',
        ]);

        // Verify all products belong to this tenant
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if ($product->tenant_id !== $this->tenantId()) {
                return response()->json(['error' => 'Produk tidak valid.'], 403);
            }
            if ($product->track_stock && $product->stock < $item['quantity']) {
                return response()->json([
                    'error' => "Stok {$product->name} tidak mencukupi. Tersedia: {$product->stock}"
                ], 422);
            }
        }

        $transaction = $this->transactionService->createTransaction(
            $request->all(),
            $this->tenantId(),
            Auth::id()
        );

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil!',
            'transaction' => $transaction,
        ]);
    }

    public function receipt(Transaction $transaction)
    {
        abort_if($transaction->tenant_id !== $this->tenantId(), 403);
        $transaction->load('items.product', 'customer', 'user', 'tenant');

        return view('owner.pos.receipt', compact('transaction'));
    }
}
