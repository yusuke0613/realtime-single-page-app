<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TantouUserResource extends JsonResource
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
            'gomiFlag'         => $this->gomiFlag,
            'souziFlag'        => $this->souziFlag,
            'seisouFlag'       => $this->seisouFlag,
            'serverSoujiFlag'  => $this->serverSoujiFlag,
            'hinomotoFlag'     => $this->hinomotoFlag,
            'updated_at'       => $this->updated_at,
            //'user' => $this->user->name,
        ];
    }
}
