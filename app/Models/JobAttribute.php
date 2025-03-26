<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAttribute extends Model
{
    use HasFactory;

    protected $table = 'attributes';
    protected $fillable = ['name', 'type', 'options'];

    public function jobAttributeValues()
    {
        return $this->hasMany(JobAttributeValue::class);
    }
}
