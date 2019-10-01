<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'id'            => $this->id,
            'locationId'    => $this->locationId,
            'locationName1' => $this->locationName1,
            'locationName2' => $this->locationName2,
            'phoneNo'       => $this->phoneNo,
            //'user' => $this->user->name,
        ];
    }
}
