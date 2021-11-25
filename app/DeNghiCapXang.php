<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeNghiCapXang extends Model
{
    //
    protected $table = "de_nghi_cap_xang";
    public function user() {
        return $this->belongsTo('App\User', 'id_user', 'id');
    }

    public function userLead() {
        return $this->belongsTo('App\User', 'lead_id', 'id');
    }
}
