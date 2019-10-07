<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TantouResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'id'               => $this->id,
            'userName'         => $this->userName,
            'touban'           => $this->touban,
            'updated_at'       => $this->updated_at,
            //'user' => $this->user->name,
        ];
    }
}
