<?php

namespace App\Http\Resources\Locations;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubLocationResource extends JsonResource
{
    protected $success;
    protected $code;
    protected $message;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sub_location_name' => $this->sub_location_name,
            'main_location' => new MainLocationResource($this->whenLoaded('mainLocation')),
        ];
    }
}
