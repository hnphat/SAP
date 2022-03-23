<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CapHoa extends Model
{
    //
    protected $table = "cap_hoa";
    public function user() {
        return $this->belongsTo('App\User','id_user','id');
    }
}
