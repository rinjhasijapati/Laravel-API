<?php

namespace App\Http\Resources\Locations;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MainLocationResource extends JsonResource
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
                'main_location_name' => $this->main_location_name,
                'area' => new AreaResource($this->whenLoaded('area')),
        ];
    }
}
