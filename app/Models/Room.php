<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'room_number',
        'room_type',
        'price',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
        ];
    }

    /**
     * Satu kamar dapat memiliki beberapa riwayat penghuni.
     */
    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }
}
