<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Locations\MainLocationStoreRequest;
use App\Http\Requests\Locations\MainLocationUpdateRequest;
use App\Http\Resources\Locations\MainLocationResource;
use App\Models\MainLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainLocationController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $searchTerm = $request->get('search', '');
        $query = MainLocation::with('area.country');

        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereHas('area', function ($query) use ($searchTerm) {
                    $query->where('area_name', 'LIKE', "%{$searchTerm}%");
                });

                $query->orWhereHas('area.country', function ($query) use ($searchTerm) {
                    $query->where('country_name', 'LIKE', "%{$searchTerm}%");
                });

                $query->orWhere('main_location_name', 'LIKE', "%{$searchTerm}%");
            });
        }

        $main_location = $query->paginate($perPage);
        return MainLocationResource::collection($main_location)->additional([
            'success' => true,
            'code' => 200,
            'message' => 'Main Locations retrieved successfully'
        ]);
    }

    public function show(MainLocation $main_location)
    {
        $main_location->load('area');
        $data = new MainLocationResource($main_location);
        return $this->success($data, "Main Location retrieved successfully");
    }

    public function store(MainLocationStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $main_location = MainLocation::create($validated);
            DB::commit();
            return $this->success($main_location, "Main Location created successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failure($e->getMessage(), "Failed to create main-location");
        }
    }

    public function update(MainLocationUpdateRequest $request, MainLocation $main_location)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $main_location->update($validated);
            DB::commit();
            return $this->success($main_location, 'Main Location updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failure($e->getMessage(), 'Failed to update main-location');
        }
    }

    public function destroy(MainLocation $main_location)
    {
        DB::beginTransaction();
        try {
            $main_location->delete();
            DB::commit();
            return $this->success($main_location, 'Main Location deleted successfully');
        } catch (\Exception $e) {
            return $this->failure($e->getMessage(), 'Failed to delete main-location');
        }
    }
}
