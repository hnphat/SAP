<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function mktguest() {
        return $this->hasMany('App\MarketingGuest','mkt_guest','id_user_create');
    }

    public function report() {
        return $this->hasMany('App\Report','user_report','id');
    }

    public function roles() {
        return $this->belongsToMany('App\Roles','role_user', 'user_id', 'role_id');
    }

    public function hasRole($role) {
        return $role = $this->roles()->where('name', $role)->exists();
    }

    public function userDetail() {
        return $this->hasOne('App\UsersDetail', 'id_user', 'id');
    }

    public function guest() {
        return $this->hasMany('App\Guest', 'id_user_create', 'id');
    }

    public function guestDichVu() {
        return $this->hasMany('App\GuestDv', 'id_user_create', 'id');
    }

    public function bieuMau() {
        return $this->hasMany('App\BieuMau', 'user_create', 'id');
    }

    public function sale() {
        return $this->hasMany('App\Sale','id_user_create','id');
    }

    public function capHoa() {
        return $this->hasMany('App\CapHoa','id_user','id');
    }

    public function carSale() {
        return $this->hasMany('App\CarSale','id_user_create','id');
    }

    public function dv() {
        return $this->hasMany('App\DV','id_user_create','id');
    }

    public function xeLaiThu() {
        return $this->hasMany('App\XeLaiThu','id_user_use','id');
    }

    public function deNghiCapXang() {
        return $this->hasMany('App\DeNghiCapXang','id_user','id');
    }

    public function dangKySuDung() {
        return $this->hasMany('App\DangKySuDung','id_user_reg','id');
    }

    public function traXe() {
        return $this->hasMany('App\TraXe','id_user_pay', 'id');
    }

    public function taiLieu() {
        return $this->hasMany('App\TaiLieu','id_user','id');
    }

    public function nhom() {
        return $this->belongsToMany('App\Nhom','nhom_user', 'id_user', 'id_nhom');
    }

    public function hasNhom($nhom) {
        return $nhom = $this->nhom()->where('name', $nhom)->exists();
    }

    public function quyen() {
        return $this->belongsTo('App\Quyen','rule', 'id');
    }

    public function workPersonal() {
        return $this->hasMany('App\ReportWork','user_tao','id');
    }

    public function workOther() {
        return $this->hasMany('App\ReportWork','user_nhan','id');
    }

    public function chamCongChiTiet() {
        return $this->hasMany('App\ChamCongChiTiet','id_user','id');
    }

    public function xinPhep() {
        return $this->hasMany('App\XinPhep','id_user','id');
    }

    public function duyetPhep() {
        return $this->hasMany('App\XinPhep','id_user_duyet','id');
    }

    public function tangCa() {
        return $this->hasMany('App\TangCa','id_user','id');
    }

    public function duyetTangCa() {
        return $this->hasMany('App\TangCa','id_user_duyet','id');
    }

    public function xacNhanCong() {
        return $this->hasMany('App\XacNhanCong','id_user','id');
    }

    public function quanLyTangCa() {
        return $this->hasMany('App\QuanLyTangCa','id_user','id');
    }

    public function chungTu() {
        return $this->hasMany('App\ChungTu', 'user_create', 'id');
    }

    public function nhatKy() {
        return $this->hasMany('App\NhatKy', 'id_create', 'id');
    }

    public function phieuNhap() {
        return $this->hasMany('App\PhieuNhap','id_user','id');
    }

    public function phieuXuat() {
        return $this->hasMany('App\PhieuXuat','id_user_xuat','id');
    }

    public function phieuXuatDuyet() {
        return $this->hasMany('App\PhieuXuat','id_user_duyet','id');
    }

    public function drpcheck() {
        return $this->hasMany('App\DRPCheck','id_user','id');
    }
}
