<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    public function hello($id)
    {


        // Validation - FormRequest
        // JsonResponse - JsonResource
        return Country::find($id);

    }

    public function submit()
    {

    }
}
