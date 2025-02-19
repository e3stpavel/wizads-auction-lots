<?php

namespace App\Http\Controllers;

use App\Http\Requests\LotRequest;
use App\Http\Resources\LotResource;
use App\Models\Lot;

class LotsController extends Controller
{
    public function listRootChildren()
    {
        return LotResource::collection(
            Lot::whereDoesntHave('containingLot')
                ->withCount(['containedLots'])
                ->get()
        );
    }

    public function listChildrenByParent(Lot $lot)
    {
        $lots = Lot::whereParentId($lot->id)
            ->withCount(['containedLots'])
            ->get();

        return LotResource::collection(
            $lots->sortBy(function (Lot $containedLot) use ($lot) {
                return $lot->children->search($containedLot->id);
            })
        );
    }

    public function createLot(Lot $lot, LotRequest $request)
    {
        $containedLot = new Lot($request->validated());
        $containedLot->containingLot()->associate($lot);

        $containedLot->save();

        // add it to the children array to preserve order
        $lot->children->push($containedLot->id);
        $lot->save();

        return new LotResource($containedLot);
    }
}
