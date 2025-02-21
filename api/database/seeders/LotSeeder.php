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
        $root = Lot::create([
            'name' => '__root__',
            'price' => 0,
        ]);

        $box = Lot::create([
            'name' => 'Garage box #4826',
            'price' => 5_000,
        ]);
        $warehouse = Lot::create([
            'name' => 'Warehouse',
            'price' => 10_000_000,
            'children' => collect([$box->id]),
        ]);
        $factory = Lot::create([
            'name' => 'Old factory',
            'price' => 50_000_000,
        ]);

        $root->children = collect([$factory, $warehouse])->map(fn (Lot $lot) => $lot->id);
        $root->save();
    }
}
