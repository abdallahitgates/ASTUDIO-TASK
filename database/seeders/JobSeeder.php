<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Job;
use App\Models\Language;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $job1 = Job::create([
            'title' => 'Senior PHP Developer',
            'description' => 'Develop and maintain PHP applications',
            'company_name' => 'ASTUDIO',
            'salary_min' => 60000,
            'salary_max' => 90000,
            'is_remote' => true,
            'job_type' => 'full-time',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $job2 = Job::create([
            'title' => 'JavaScript Engineer',
            'description' => 'Frontend and backend JavaScript development',
            'company_name' => 'WebSoft',
            'salary_min' => 70000,
            'salary_max' => 110000,
            'is_remote' => false,
            'job_type' => 'full-time',
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Attach relationships
        $php = Language::where('name', 'PHP')->first();
        $js = Language::where('name', 'JavaScript')->first();
        $ny = Location::where('city', 'New York')->first();
        $remote = Location::where('city', 'Remote')->first();
        $devCategory = Category::where('name', 'Software Development')->first();

        $job1->languages()->attach([$php->id, $js->id]);
        $job1->locations()->attach([$ny->id, $remote->id]);
        $job1->categories()->attach([$devCategory->id]);

        $job2->languages()->attach([$js->id]);
        $job2->locations()->attach([$ny->id]);
        $job2->categories()->attach([$devCategory->id]);
    }
}
