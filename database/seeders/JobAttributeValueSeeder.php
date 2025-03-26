<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Job;
use App\Models\JobAttribute;
use App\Models\JobAttributeValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobAttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $yearsExperience = JobAttribute::where('name', 'years_experience')->first();

        $job1 = Job::where('title', 'Senior PHP Developer')->first();
        $job2 = Job::where('title', 'JavaScript Engineer')->first();

        JobAttributeValue::insert([
            ['job_id' => $job1->id, 'attribute_id' => $yearsExperience->id, 'value' => 5],
            ['job_id' => $job2->id, 'attribute_id' => $yearsExperience->id, 'value' => 3],
        ]);
    }
}
