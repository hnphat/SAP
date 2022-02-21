<?php

use Illuminate\Database\Seeder;

class LoaiPhepSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
               DB::table('loai_phep')->insert([
                  [
                      'id' => 1,
                      'tenPhep' => "Phép Năm",
                      'maPhep' => "PN",
                      'loaiPhep' => "PHEPNAM",
                      'moTa' => ""
                  ],
                  [
                    'id' => 2,
                    'tenPhep' => "Công tác",
                    'maPhep' => "H",
                    'loaiPhep' => "COLUONG",
                    'moTa' => ""
                  ],
                  [
                    'id' => 3,
                    'tenPhep' => "Vào trể việc Công ty",
                    'maPhep' => "BVT",
                    'loaiPhep' => "COLUONG",
                    'moTa' => ""
                  ],
                  [
                    'id' => 4,
                    'tenPhep' => "Quên Chấm Công",
                    'maPhep' => "QCC",
                    'loaiPhep' => "QCC",
                    'moTa' => ""
                  ],
                  [
                    'id' => 5,
                    'tenPhep' => "Xin nghỉ phép",
                    'maPhep' => "P",
                    'loaiPhep' => "KHONGLUONG",
                    'moTa' => ""
                  ],
                  [
                    'id' => 6,
                    'tenPhep' => "Vào trể việc cá nhân",
                    'maPhep' => "VT",
                    'loaiPhep' => "KHONGLUONG",
                    'moTa' => ""
                  ],
                  [
                    'id' => 7,
                    'tenPhep' => "Về sớm việc cá nhân",
                    'maPhep' => "VS",
                    'loaiPhep' => "KHONGLUONG",
                    'moTa' => ""
                  ],
                  [
                    'id' => 8,
                    'tenPhep' => "Làm thêm",
                    'maPhep' => "LT",
                    'loaiPhep' => "KHONGLUONG",
                    'moTa' => ""
                  ],
                  [
                    'id' => 9,
                    'tenPhep' => "Về sớm việc Công ty",
                    'maPhep' => "BVS",
                    'loaiPhep' => "COLUONG",
                    'moTa' => ""
                  ],
                  [
                    'id' => 10,
                    'tenPhep' => "Nghĩ lễ quy định",
                    'maPhep' => "L",
                    'loaiPhep' => "COLUONG",
                    'moTa' => ""
                  ],
                  [
                    'id' => 11,
                    'tenPhep' => "Nghĩ lễ không lương",
                    'maPhep' => "LKL",
                    'loaiPhep' => "KHONGLUONG",
                    'moTa' => ""
                  ],
                  [
                    'id' => 12,
                    'tenPhep' => "Nghĩ Phép Có Lương",
                    'maPhep' => "PCL",
                    'loaiPhep' => "COLUONG",
                    'moTa' => ""
                  ]                  
               ]);
    }
}
