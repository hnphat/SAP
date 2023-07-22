<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketingGuest extends Model
{
    //
    protected $table = "mkt_guest";
    public function user() {
        return $this->belongsTo('App\User', 'id_user_create', 'id');
    }
}
