<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'logo', 'address', 'phone', 'email',
        'description', 'status', 'plan', 'plan_expires_at', 'settings',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'plan_expires_at' => 'date',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function owner()
    {
        return $this->users()->where('role', 'owner')->first();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getTotalRevenueAttribute(): float
    {
        return $this->transactions()->where('status', 'completed')->sum('total');
    }
}
