<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Locations\AreaStoreRequest;
use App\Http\Requests\Locations\AreaUpdateRequest;
use App\Http\Resources\Locations\AreaResource;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $searchTerm = $request->get('search', '');
        $query = Area::with('country');

        if($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereHas('country', function ($query) use ($searchTerm) {
                    $query->where('country_name', 'LIKE', "%{$searchTerm}%");
                });

                $query->orWhere('area_name', 'LIKE', "%{$searchTerm}%");
            });
        }

        $area = $query->paginate($perPage);
        return AreaResource::collection($area)->additional([
            'success' => true,
            'code' => 200,
            'message' => 'Area retrieved successfully'
        ]);
    }

    public function show(Area $area)
    {
        $area->load('country');
        return $this->success(new AreaResource($area), "Area retrieved successfully");
    }

    public function store(AreaStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $area = Area::create($validated);
            $area->load('country');
            DB::commit();
            return $this->success(new AreaResource($area), "Area created successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failure($e->getMessage(), 'Failed to create area');
        }
    }

    public function update(AreaUpdateRequest $request, Area $area)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $area->update($validated);
            $area->load('country');
            DB::commit();
            return $this->success(new AreaResource($area), "Area updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failure($e->getMessage(), 'Failed to update area');
        }
    }

    public function destroy(Area $area)
    {
        DB::beginTransaction();
        try {
            $area->delete();
            DB::commit();
            return $this->success($area, 'Areas deleted successfully');
        } catch (\Exception $e) {
            return $this->failure($e->getMessage(), 'Failed to delete area');
        }
    }
}
