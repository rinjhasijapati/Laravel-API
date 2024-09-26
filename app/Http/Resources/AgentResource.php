<?php

namespace App\Http\Resources;

use App\Http\Resources\Locations\AreaResource;
use App\Http\Resources\Locations\CountryResource;
use App\Http\Resources\Locations\MainLocationResource;
use App\Http\Resources\Locations\SubLocationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AgentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'phone_no' => $this->phone_no,
                'alternate_phone_no' => $this->alternate_phone_no,
                'contact_person' => $this->contact_person,
                'sub_location' => new SubLocationResource($this->whenLoaded('subLocation')),
        ];
    }
}








