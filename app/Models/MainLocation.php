<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainLocation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'main_location_name',
        'area_id',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function sublocation()
    {
        return $this->hasMany(Sublocation::class);
    }
}
