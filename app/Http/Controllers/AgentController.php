<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgentStoreRequest;
use App\Http\Requests\AgentUpdateRequest;
use App\Http\Resources\AgentResource;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $searchTerm = $request->get('search', '');
        $query = Agent::with('subLocation.mainLocation.area.country');


        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                    $query->orWhereHas('subLocation', function ($query) use ($searchTerm) {
                        $query->where('sub_location_name', 'LIKE', "%{$searchTerm}%");
                    });

                    $query->orWhereHas('subLocation.mainLocation', function ($query) use ($searchTerm) {
                        $query->where('main_location_name', 'LIKE', "%{$searchTerm}%");
                    });

                    $query->orWhereHas('subLocation.mainLocation.area', function ($query) use ($searchTerm) {
                        $query->where('area_name', 'LIKE', "%{$searchTerm}%");
                    });

                    $query->orWhereHas('subLocation.mainLocation.area.country', function ($query) use ($searchTerm) {
                        $query->where('country_name', 'LIKE', "{$searchTerm}%");
                    });

                    $query->orWhere('name', 'LIKE', "%{$searchTerm}%");
                    $query->orWhere('email', 'LIKE', "%{$searchTerm}%");
                    $query->orWhere('phone_no', 'LIKE', "%{$searchTerm}%");
                    $query->orWhere('alternate_phone_no', 'LIKE', "%{$searchTerm}%");
                    $query->orWhere('contact_person', 'LIKE', "%{$searchTerm}%");
                });
        }


        $agents = $query->paginate($perPage);
        return AgentResource::collection($agents)->additional([
            'success' => true,
            'code' => 200,
            'message' => 'Agents retrieved successfully',
        ]);
    }

    public function show(Agent $agent)
    {
        $agent->load('subLocation.mainLocation.area.country');
        return $this->success(new AgentResource($agent), "Agent retrieved successfully");
    }

    public function store(AgentStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $agent = Agent::create($validated);
            $agent->load('subLocation.mainLocation.area.country');
            DB::commit();
            return $this->success(new AgentResource($agent), "Agent created successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failure($e->getMessage(), "Failed to create Agent");
        }
    }

    public function update(AgentUpdateRequest $request, Agent $agent)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $agent->update($validated);
            $agent->load('subLocation.mainLocation.area.country');
            DB::commit();
            return $this->success(new AgentResource($agent), "Agent updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failure($e->getMessage(), "Failed to update Agent");
        }
    }

    public function destroy(Agent $agent)
    {
        DB::beginTransaction();
        try {
            $agent->delete();
            DB::commit();
            return $this->success($agent, "Agent deleted successfully");
        } catch (\Exception $e) {
            return $this->failure($e->getMessage(), "Failed to delete Agent");
        }
    }
}




































