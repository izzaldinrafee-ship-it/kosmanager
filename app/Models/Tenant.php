<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Room;
use App\Models\Payment;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'room_id',
        'move_in_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'move_in_date' => 'date',
        ];
    }

    /**
     * Kamar yang ditempati atau pernah ditempati penghuni.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Riwayat tagihan milik penghuni.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
