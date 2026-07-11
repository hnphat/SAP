<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaoHiemHopDong extends Model
{
    protected $table = "baohiem_hopdong";

    /**
     * Quan hệ với khách hàng bảo hiểm.
     */
    public function guest() {
        return $this->belongsTo('App\GuestBaoHiem', 'id_guest_baohiem', 'id');
    }

    /**
     * Quan hệ với user tạo bản ghi.
     */
    public function creator() {
        return $this->belongsTo('App\User', 'id_user_create', 'id');
    }
}
