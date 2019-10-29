<?php

namespace App\Model;
use App\User;

use Illuminate\Database\Eloquent\Model;

class DashBoardUser extends Model
{
    public function getRouteKeyName() {
        return 'comentNum';
    }
    protected $guarded = [];
    public function getPathAttribute() {
        return "/dashboarduse/$this->comentNum";
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

}
