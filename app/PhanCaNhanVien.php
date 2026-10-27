<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhanCaNhanVien extends Model
{
    protected $table = 'phan_ca_nhan_vien';
    protected $fillable = [
        'id_user', 'id_ca_lam', 'ghi_chu'
    ];

    public function user() {
        return $this->belongsTo('App\User', 'id_user', 'id');
    }

    public function caLam() {
        return $this->belongsTo('App\CaLam', 'id_ca_lam', 'id');
    }
}
