<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Contracts\Database\Eloquent\Builder;

class LocationsController extends Controller
{
    public function listRoot()
    {
        return Location::whereNull('parent_location_id')
//            ->with(['locations' => function (Builder $query) {
//                $query->orderBy('item_order');
//            }])
            ->withCount('locations')
            ->orderBy('item_order')
            ->get();
    }

    public function listChildren(int $parentId) {
        return Location::where('parent_location_id', $parentId)
            ->withCount('locations')
            ->orderBy('item_order')
            ->get();
    }
}
