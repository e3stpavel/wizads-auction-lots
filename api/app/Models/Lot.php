<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    protected $fillable = [
        'name',
        'price',
    ];

    protected $attributes = [
        'children' => '[]',
    ];

    /**
     * @return Collection<Lot>
     */
    public function containedLots(): Collection
    {
        $self = $this;

        // there is some package but I won't install it
        return Lot::whereIn('id', $this->children)
            ->get()
            ->sortBy(function (Lot $lot) use ($self) {
                return $self->children->search($lot->id);
            });
    }

    public function containingLot(): ?Lot
    {
        return Lot::whereJsonContains('children', $this->id)
            ->first();
    }

    protected $casts = [
        'children' => AsCollection::class,
    ];
}
