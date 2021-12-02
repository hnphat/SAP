<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageV2 extends Model
{
    //
    protected $table = "packagev2";

    public function hopDong() {
        return $this->belongsToMany('App\HopDong', 'saleoffv2', 'id_bh_pk_package','id_hd');
    }
}
