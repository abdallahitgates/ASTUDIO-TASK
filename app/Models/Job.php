<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    protected $table = 'jobs';
    protected $fillable = [
        'title', 'description', 'company_name',
        'salary_min', 'salary_max', 'is_remote',
        'job_type', 'status', 'published_at'
    ];

    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function jobAttributeValues()
    {
        return $this->hasMany(JobAttributeValue::class);
    }
}
