<?php

namespace App\Http\Resources;

use App\Models\Lot;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

/** @mixin Lot */
class LotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $containedLotsCount = $this->whenCounted('contained_lots');

        // TODO: this is a quick fix btw
        if ($containedLotsCount instanceof MissingValue && $containedLotsCount->isMissing()) {
            $containedLotsCount = 0;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'containedLots' => $this->when(
                $containedLotsCount > 0, [], null
            ),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
