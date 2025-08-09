<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class XeCuuHo extends Model
{
    //
    protected $table = "xe_cuu_ho";
    
    public function user() {
        return $this->belongsTo('App\User','id_user', 'id');
    }
}
