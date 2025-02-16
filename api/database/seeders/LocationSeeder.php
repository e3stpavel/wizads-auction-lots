<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    private function getRandomPrice(): float
    {
        $price = mt_rand(9.99 * 100, 99.99 * 100) / 100;

        return number_format($price, 2, '.', '');
    }

    private function createLocation(string $name, int $order): Location
    {
        return Location::create([
            'name' => $name,
            'price' => $this->getRandomPrice(),
            'item_order' => $order,
        ]);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $city = $this->createLocation('Miami', 1);
        $city->locations()->saveMany([
            $this->createLocation('Downtown Server', 1),
            $this->createLocation('Server near the coast', 2),
        ]);

        $state = $this->createLocation('Florida, FL', 1);
        $state->locations()->save($city);

        $country = $this->createLocation('USA', 1);
        $country->locations()->saveMany([
            $state,
            $this->createLocation('California, CA', 2),
            $this->createLocation('Texas, TX', 3),
        ]);

        $continent = $this->createLocation('North America', 1);
        $continent->locations()
            ->saveMany([
                $this->createLocation('Canada', 2),
                $country,
            ]);

        $this->createLocation('Europe', 2);
        $this->createLocation('Asia', 3);
    }
}
