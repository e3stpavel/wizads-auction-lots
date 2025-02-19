<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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

    public function listChildren(int $parentId)
    {
        return Location::where('parent_location_id', $parentId)
            ->withCount('locations')
            ->orderBy('item_order')
            ->get();
    }

    public function createRoot(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1|max:255',
            'price' => 'required|numeric|gt:0',
        ]);

        $last = Location::whereNull('parent_location_id')
            ->orderBy('item_order', 'desc')
            ->first();

        return Location::create([
            'name' => $request->name,
            'price' => $request->price,
            'item_order' => $last->item_order + 1,
        ]);
    }

    public function createChild(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1|max:255',
            'price' => 'required|numeric|gt:0',
            'parent_location_id' => 'integer|gt:0',
        ]);

        $parent = Location::where('id', $request->parent_location_id)
            ->with(['locations' => function (Builder $query) {
                $query->orderBy('item_order', 'desc');
            }])
            ->firstOrFail();

        $location = Location::create([
            'name' => $request->name,
            'price' => $request->price,
            'item_order' => $parent->locations()->first()->item_order + 1,
        ]);

        $parent->locations()->save($location);
        return $location;
    }
}
