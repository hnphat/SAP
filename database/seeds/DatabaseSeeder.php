<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(User::class);
        $this->call(UserDetail::class);
//        $this->call(TypeCarSeeder::class);
//        $this->call(TypeCarDetailSeeder::class);
        $this->call(TypeGuestSeeder::class);
//        $this->call(GuestSeeder::class);
//        $this->call(CarSaleSeeder::class);
//        $this->call(SaleSeeder::class);
//        $this->call(BhPkPackageSeeder::class);
//        $this->call(SaleOffSeeder::class);
//        $this->call(GuestDvSeeder::class);
//        $this->call(DvSeeder::class);
//        $this->call(LoaiCongSeeder::class);
//        $this->call(LoaiPhuTungSeeder::class);
//        $this->call(CongSeeder::class);
//        $this->call(PhuTungSeeder::class);
//        $this->call(XeLaiThuSeeder::class);
//        $this->call(DangKyLaiThuSeeder::class);
//        $this->call(TraXeSeeder::class);
//        $this->call(DeNghiCapXangSeeder::class);
//        $this->call(TaiLieuSeeder::class);
//        $this->call(NhomSeeder::class);
//        $this->call(NhomUserSeeder::class);
//        $this->call(QuyenXemSeeder::class);
        $this->call(RoleSeed::class);
        $this->call(LoaiPhepSeed::class);

    }
}

