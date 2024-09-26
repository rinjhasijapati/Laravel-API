<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubLocation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'sub_location_name',
        'main_location_id',
    ];

    public function mainLocation()
    {
        return $this->belongsTo(MainLocation::class);
    }

    public function Agent()
    {
        return $this->hasMany(Agent::class);
    }
}
