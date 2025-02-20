<?php

namespace App\Http\Resources;

use App\Models\Lot;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Lot */
class LotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,

            // because I am designing the API for my frontend
            //  I believe it's alright to include some internal conventions
            'containedLots' => $this->when(
                $this->children?->count() > 0, [], null
            ),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
