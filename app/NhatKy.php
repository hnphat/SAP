<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NhatKy extends Model
{
    //
    protected $table = "nhat_ky";
    public function user() {
        return $this->belongsTo('App\User', 'id_create', 'id');
    }
}
