<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryHopDong extends Model
{
    //
    protected $table = "history_hopdong";

    public function user() {
        return $this->belongsTo('App\User', 'id_user', 'id');
    }

    public function hopDong() {
        return $this->belongsTo('App\HopDong', 'idDeNghi', 'id');
    }
}
