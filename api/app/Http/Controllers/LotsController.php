<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lot\CreateLotRequest;
use App\Http\Requests\Lot\PatchLotRequest;
use App\Http\Resources\LotResource;
use App\Models\Lot;

class LotsController extends Controller
{
    private function getRootLot(): Lot
    {
        return Lot::whereName('__root__')->firstOrFail();
    }

    public function listLots(?Lot $lot = null)
    {
        if ($lot === null) {
            $lot = $this->getRootLot();
        }

        return LotResource::collection($lot->containedLots());
    }

    public function createLot(CreateLotRequest $request, ?Lot $lot = null)
    {
        if ($lot === null) {
            $lot = $this->getRootLot();
        }

        $containedLot = Lot::create($request->validated());

        // add it to the children array to establish relationship and preserve order
        $lot->children->push($containedLot->id);
        $lot->save();

        return new LotResource($containedLot);
    }

    public function updateLot(CreateLotRequest $request, Lot $lot)
    {
        abort_if($lot->name === '__root__', 400, 'Bad Request');

        $lot->fill($request->validated());
        $lot->save();

        return new LotResource($lot);
    }

    /**
     * Changes the order of lots contained in a lot provided by the request.
     * The following will not move the item out of its parent, therefore PATCH is used.
     */
    public function moveContainedLots(PatchLotRequest $request, ?Lot $lot = null)
    {
        if ($lot === null) {
            $lot = $this->getRootLot();
        }

        $reorderedChildren = collect($request->validated()['value']);

        abort_if(
            $reorderedChildren->diff($lot->children)->isNotEmpty(),
            400,
            'Bad Request'
        );

        $lot->children = $reorderedChildren;
        $lot->save();

        return LotResource::collection($lot->containedLots());
    }

    public function deleteLot(Lot $lot)
    {
        abort_if($lot->name === '__root__', 400, 'Bad Request');

        // delete parent relationship
        $containingLot = $lot->containingLot();
        $containingLot->children = $containingLot->children
            ->reject($lot->id)
            ->values();
        $containingLot->save();

        // I will not perform cascade delete here for now
        //  as it will probably destroy db if the lot has really long nested list
        //  this means that orphans will be still in the database
        //  one way is to use pruning and clear them on the background later
        $lot->delete();
        return response()->noContent();
    }
}
