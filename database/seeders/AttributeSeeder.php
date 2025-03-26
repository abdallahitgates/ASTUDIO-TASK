<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\JobAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JobAttribute::insert(
            [
                ['name' => 'years_experience', 'type' => 'number', 'options' => null],
                ['name' => 'certifications', 'type' => 'select', 'options' => json_encode(['AWS', 'Google Cloud', 'PMP'])],
            ]
        );
    }
}
