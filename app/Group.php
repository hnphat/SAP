<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    protected $table = "group";

    public function user() {
        return $this->belongsToMany('App\User','group_sale', 'group_id', 'user_id');
    }
}
