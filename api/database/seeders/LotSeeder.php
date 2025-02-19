<?php

namespace Database\Seeders;

use App\Models\Lot;
use Illuminate\Database\Seeder;

class LotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lot::create([
            'name' => 'empty',
            'price' => 10.9,
        ]);

        $root = Lot::create([
            'name' => 'root',
            'price' => 0,
        ]);

        $warehouse = Lot::create([
            'name' => 'Warehouse',
            'price' => 10_000_000,
        ]);
        $factory = Lot::create([
            'name' => 'Old factory',
            'price' => 50_000_000,
        ]);

        $root->containedLots()->saveMany([$warehouse, $factory]);
        $root->children = collect([$factory, $warehouse])->map(function (Lot $lot) {
            return $lot->id;
        });

        $root->save();
    }
}
