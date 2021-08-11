<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DangKySuDung extends Model
{
    //
    protected $table = "dang_ky_su_dung";

    public function xeLaiThu() {
        return $this->belongsTo('App\XeLaiThu', 'id_xe_lai_thu', 'id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'id_user_reg', 'id');
    }
}
