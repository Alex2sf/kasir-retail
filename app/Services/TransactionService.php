<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function createTransaction(array $data, int $tenantId, int $userId): Transaction
    {
        return DB::transaction(function () use ($data, $tenantId, $userId) {
            $invoiceNumber = Transaction::generateInvoiceNumber($tenantId);

            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $subtotal += ($item['price'] * $item['quantity']) - ($item['discount'] ?? 0);
            }

            $discountAmount = $data['discount_amount'] ?? 0;
            $taxAmount = $data['tax_amount'] ?? 0;
            $total = $subtotal - $discountAmount + $taxAmount;
            $paidAmount = $data['paid_amount'];
            $changeAmount = $paidAmount - $total;

            $transaction = Transaction::create([
                'tenant_id' => $tenantId,
                'user_id' => $userId,
                'customer_id' => $data['customer_id'] ?? null,
                'invoice_number' => $invoiceNumber,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'discount_type' => $data['discount_type'] ?? null,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'paid_amount' => $paidAmount,
                'change_amount' => max(0, $changeAmount),
                'payment_method' => $data['payment_method'] ?? 'cash',
                'status' => 'completed',
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $product = Product::find($item['product_id']);
                $itemDiscount = $item['discount'] ?? 0;
                $itemSubtotal = ($item['price'] * $item['quantity']) - $itemDiscount;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $itemDiscount,
                    'subtotal' => $itemSubtotal,
                ]);

                // Reduce stock
                if ($product->track_stock) {
                    $stockBefore = $product->stock;
                    $product->decrement('stock', $item['quantity']);

                    StockMovement::create([
                        'tenant_id' => $tenantId,
                        'product_id' => $product->id,
                        'user_id' => $userId,
                        'type' => 'sale',
                        'quantity' => $item['quantity'],
                        'stock_before' => $stockBefore,
                        'stock_after' => $product->fresh()->stock,
                        'reference' => $invoiceNumber,
                        'notes' => 'Penjualan kasir',
                    ]);
                }
            }

            // Update customer stats if applicable
            if ($transaction->customer_id) {
                $customer = $transaction->customer;
                $customer->increment('total_spent', $total);
                $customer->increment('total_transactions');
            }

            return $transaction->load('items.product', 'customer', 'user');
        });
    }
}
