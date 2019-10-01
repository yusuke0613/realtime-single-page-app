<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashBordUserResource extends JsonResource
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
            'displayId'     => $this->displayId,
            'displayName'   => $this->displayName,
            'status'        => $this->status,
            'firstName'     => $this->firstName,
            'lastName'      => $this->lastName,
            'rankNo'        => $this->rankNo,
            'rankName'      => $this->rankName,
            'phoneNo'       => $this->phoneNo,
            'belongsId'     => $this->belongsId,
            'belongsName'   => $this->belongsName,
            'mail'          => $this->mail,
            'locationId'    => $this->locationId,
            'location'      => $this->location,
            'comentNum'     => $this->comentNum,
            'seisouFlag'    => $this->seisouFlag,
            'soujiFlag'     => $this->soujiFlag,
            'garbageFlag'   => $this->garbageFlag,
            //'user' => $this->user->name,
        ];
    }
}
