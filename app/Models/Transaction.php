<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'user_id', 'customer_id', 'invoice_number',
        'subtotal', 'discount_amount', 'discount_type', 'tax_amount',
        'total', 'paid_amount', 'change_amount', 'payment_method',
        'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'change_amount' => 'decimal:2',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public static function generateInvoiceNumber(int $tenantId): string
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        
        $latest = static::where('tenant_id', $tenantId)
            ->whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        if ($latest) {
            $latestCount = (int) substr($latest->invoice_number, -4);
            $count = $latestCount + 1;
        } else {
            $count = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $date, $count);
    }
}
