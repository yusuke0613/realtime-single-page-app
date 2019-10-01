<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['id', 'locationId', 'locationName1', 'locationName2', 'phoneNo'];
    public function getRouteKeyName() {
        return 'id';
    }
}
