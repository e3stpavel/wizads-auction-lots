<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lot extends Model
{
    protected $fillable = [
        'name',
        'price',
        'children',
    ];

    public function containingLot(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function containedLots(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    protected $casts = [
        'children' => AsCollection::class,
    ];
}
