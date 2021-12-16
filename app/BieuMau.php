<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BieuMau extends Model
{
    //
    protected $table = "bieu_mau";

    public function userCreate() {
        return $this->hasBelongsTo('App\User','user_create','id');
    }
}
