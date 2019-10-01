<?php

namespace App\Model;
use App\User;

use Illuminate\Database\Eloquent\Model;

class DashBoardUser extends Model
{
    public function getRouteKeyName() {
        return 'id';
    }
    protected $guarded = [];
    public function getPathAttribute() {
        return "/dashboarduse/$this->id";
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

}
