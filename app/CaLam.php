<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaLam extends Model
{
    protected $table = 'ca_lam';
    protected $fillable = [
        'ten_ca', 'vao_sang', 'ra_sang', 'vao_chieu', 'ra_chieu', 'ghi_chu'
    ];

    public function phanCa() {
        return $this->hasMany('App\PhanCaNhanVien', 'id_ca_lam', 'id');
    }
}
