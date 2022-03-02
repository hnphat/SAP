<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DanhMucSP extends Model
{
    //
    protected $table = "danhmuc_sp";
    public function nhom() {
        return $this->belongsTo('App\NhomSP','id_nhom','id');
    }
}
