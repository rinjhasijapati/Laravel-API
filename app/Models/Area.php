<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'area_name',
        'country_id',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }


    public function mainlocaion()
    {
        return $this->hasMany(MainLocation::class);
    }
}
