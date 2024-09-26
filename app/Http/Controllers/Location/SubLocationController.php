<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Locations\SubLocationStoreRequest;
use App\Http\Requests\Locations\SubLocationUpdateRequest;
use App\Http\Resources\Locations\SubLocationResource;
use App\Models\SubLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubLocationController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $searchTerm = $request->get('search', '');
        $query = SubLocation::with('mainLocation.area.country');

        if($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereHas('mainLocation', function ($query) use ($searchTerm) {
                    $query->where('main_location_name', 'LIKE', "%{$searchTerm}%");
                });

                $query->orWhereHas('mainLocation.area', function ($query) use ($searchTerm) {
                    $query->where('area_name', 'LIKE', "%{$searchTerm}%");
                });

                $query->orWhereHas('mainLocation.area.country', function ($query) use ($searchTerm) {
                    $query->where('country_name', 'LIKE', "%{$searchTerm}%");
                });

                $query->orWhere('sub_location_name', 'LIKE', "%{$searchTerm}%");
            });
        }

        $sub_location = $query->paginate($perPage);
        return SubLocationResource::collection($sub_location)->additional([
            'success' => true,
            'code' => 200,
            'message' => 'Sub Locations retrieved successfully'
        ]);
    }

    public function show(SubLocation $sub_location)
    {
        $sub_location->load('mainLocation');
        $data = new SubLocationResource($sub_location);
        return $this->success($data, "Sub location retrieved successfully");
    }

    public function store(SubLocationStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $sub_location = SubLocation::create($validated);
            DB::commit();
            return $this->success($sub_location, "Sub location created successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failure($e->getMessage(), "Failed to create sub-location");
        }
    }

    public function update(SubLocationUpdateRequest $request, SubLocation $sub_location)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $sub_location->update($validated);
            DB::commit();
            return $this->success($sub_location, "Sub location updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failure($e->getMessage(), "Failed to update sub-location");
        }
    }

    public function destroy(SubLocation $sub_location)
    {
        DB::beginTransaction();
        try {
            $sub_location->delete();
            DB::commit();
            return $this->success($sub_location, "Sub location deleted successfully");
        } catch (\Exception $e) {
            return $this->failure($e->getMessage(), "Failed to delete sub-location");
        }
    }
}
