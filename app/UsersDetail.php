<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersDetail extends Model
{
    //
    protected $table = "users_detail";

    public function user() {
        return $this->hasOne('App\User','id_user', 'id');
    }
}
