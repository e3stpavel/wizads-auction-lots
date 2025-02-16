<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = [
        'name',
        'price',
        'item_order',
    ];

    public function locations(): HasMany
    {
        return $this->hasMany(self::class, 'parent_location_id');
    }

    public function parentLocation(): BelongsTo
    {
        return $this->belongsTo(self::class, 'id');
    }

    public function move(int $positionOffset): void
    {
        throw new \BadMethodCallException('not implemented');
    }
}
