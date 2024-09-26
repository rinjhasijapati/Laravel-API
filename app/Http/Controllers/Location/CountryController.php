<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Locations\CountryStoreRequest;
use App\Http\Requests\Locations\CountryUpdateRequest;
use App\Http\Resources\Locations\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->get('per_page', 15);
        $searchTerm = $request->get('search', '');
        $query = Country::query();

        if($searchTerm) {
                $query->where('country_name', 'LIKE', "%{$searchTerm}%");
        }

        $country = $query->paginate($per_page);
        return CountryResource::collection($country)->additional([
            'success' => true,
            'code' => 200,
            'message' => 'Countries retrieved successfully'
        ]);
    }


    public function show(Country $country)
    {
        $data = new CountryResource($country);
        return $this->success($data, "Country retrieved successfully");
    }

    public function store(CountryStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $country = Country::create($validated);
            DB::commit();
            return $this->success($country, "Country created successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failure($e->getMessage(), 'Failed to create country');
        }
    }

    public function update(CountryUpdateRequest $request, Country $country)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $country->update($validated);
            DB::commit();
            return $this->success($country, "Country updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failure($e->getMessage(), 'Failed to update country');
        }
    }

    public function destroy(Country $country)
    {
        DB::beginTransaction();
        try {
            $country->delete();
            DB::commit();
            return $this->success($country, "Country deleted successfully");
        } catch (\Exception $e) {
            return $this->failure($e->getMessage(), 'Failed to delete country');
        }
    }
}
