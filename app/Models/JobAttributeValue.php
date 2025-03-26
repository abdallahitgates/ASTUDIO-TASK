<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAttributeValue extends Model
{
    use HasFactory;
    
    protected $table = 'job_attribute_values';
    protected $fillable = ['job_id', 'attribute_id', 'value'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function attribute()
    {
        return $this->belongsTo(JobAttribute::class);
    }
}
