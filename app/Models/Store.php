<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Store extends Model
{
    protected $fillable = [
        'name',
        'merchant_id',
        'activity',
        'lat',
        'lng',
        'address',
        'commercial_register',
        'currency',
        'store_size',
    ];


    // Relationship
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }
}
