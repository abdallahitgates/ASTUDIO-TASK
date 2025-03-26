<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::insert(
            [
                ['city' => 'New York', 'state' => 'NY', 'country' => 'USA'],
                ['city' => 'San Francisco', 'state' => 'CA', 'country' => 'USA'],
                ['city' => 'Remote', 'state' => '', 'country' => 'Global'],
            ]
        );
    }
}
