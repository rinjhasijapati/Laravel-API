<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone_no',
        'alternate_phone_no',
        'contact_person',
        'sub_location_id',
    ];

    public function subLocation()
    {
        return $this->belongsTo(SubLocation::class);
    }
}
