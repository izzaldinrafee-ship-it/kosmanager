<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $fillable = [
        'tenant_id',
        'billing_month',
        'billing_year',
        'amount',
        'paid_amount',
        'due_date',
        'status',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'billing_month' => 'integer',
            'billing_year' => 'integer',
            'amount' => 'integer',
            'paid_amount' => 'integer',
            'due_date' => 'date',
            'paid_at' => 'date',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function getRemainingAmountAttribute(): int
    {
        return max(0, $this->amount - $this->paid_amount);
    }
}