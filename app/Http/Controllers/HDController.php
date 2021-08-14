<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class HDController extends Controller
{
    //
    public function index() {
        return view('page.hd');
    }

    public function getList() {
        $result = CarSale::select('car_sale.*','t.name as ten')->join('type_car_detail as t','car_sale.id_type_car_detail','=','t.id')->get();
        if($result) {
            return response()->json([
                'message' => 'Get list successfully!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function add(Request $request) {
        $package = new CarSale;

        $package->id_type_car_detail = $request->tenXe;
        $package->year = $request->nam;
        $package->vin = $request->vin;
        $package->frame = $request->frame;
        $package->color = $request->color;
        $package->gear = $request->gear;
        $package->machine = $request->machine;
        $package->seat = $request->seat;
        $package->fuel = $request->fuel;
        $package->cost = $request->cost;
        $package->id_user_create = Auth::user()->id;
        $package->save();

        if($package) {
            return response()->json([
                'message' => 'Insert data successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }

    }

    public function delete(Request $request) {
        $result = CarSale::where('id', $request->id)->delete();
        if($result) {
            return response()->json([
                'message' => 'Delete data successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function editShow(Request $request) {
        $result = CarSale::where('id', $request->id)->first();
        if($result) {
            return response()->json([
                'message' => 'Show edit data successfully!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function update(Request $request) {
        $result = CarSale::where('id', $request->eid)->update([
            "id_type_car_detail" => $request->etenXe,
            "year" => $request->enam,
            "vin" => $request->evin,
            "frame" => $request->eframe,
            "color" => $request->ecolor,
            "gear" => $request->egear,
            "machine" => $request->emachine,
            "seat" => $request->eseat,
            "fuel" => $request->efuel,
            "cost" => $request->ecost,
        ]);
        if($result) {
            return response()->json([
                'message' => 'Updated successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function test() {
//        $phpWord = new PhpWord();
//
//        $section = $phpWord->addSection();
//
//        $description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
//tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
//quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
//consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
//cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
//proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
//
//        $section->addImage("http://itsolutionstuff.com/frontTheme/images/logo.png");
//        $section->addText($description);
//
//        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
//        try {
//            $objWriter->save(storage_path('helloWorld.docx'));
//        } catch (Exception $e) {
//
//        }
//
//        return response()->download(storage_path('helloWorld.docx'));

        $templateProcessor = new TemplateProcessor('template/CN_HD_TM.docx');
        // Cá nhân
        $templateProcessor->setValues([
            'soHopDong' => '878782321',
            'ngay' => '08',
            'thang' => '08',
            'nam' => '2021',
            'sale' => 'Trần Bảo Toàn',
            'salePhone' => '0989 998 998',
            'guest' => 'Nguyễn Hồng Phương',
            'diaChi' => 'Long Xuyên, An Giang',
            'dienThoai' => '0918 988 998',
            'cmnd' => '351 878 877',
            'ngayCap' => '01/02/2010',
            'noiCap' => 'CA. An Giang',
            'ngaySinh' => '16/05/1984',
            'tenDaiDien' => 'Nguyễn Hồng Phương',
//            'noiDung' => 'Xe Accent 2021 Full Vàng Cát </w:t><w:p/><w:t> GHKS08767321 </w:t><w:p/><w:t> GK0878321. ',
            'noiDung' => 'Xe Accent 2021 Full Vàng Cát <w:br/>GHKS08767321 <w:br/>GK0878321. ',
            'donGia' => '326.000.000',
//            'truocBa' => '26.000.000',
//            'ttruocBa' => '26.000.000',
//            'duongBo' => '1.550.000',
//            'tduongBo' => '1.550.000',
//            'tnns' => '2.500.000',
//            'ttnns' => '2.500.000',
//            'bhtx' => '1.500.000',
//            'tbhtx' => '1.500.000',
//            'bienSo' => '350.000',
//            'tbienso' => '350.000',
//            'dangKiem' => '2.500.000',
//            'tdangKiem' => '2.500.000',
            'thanhTien' => '326.000.000',
            'phuKien' => '4.500.000',
            'giaPhuKien' => '4.500.00',
            'tongCong' => '326.000.000',
            'phukien' => 'Túi chống sốc, ba lô, thảm lót chân, trải sàn, bình chữa cháy',
            'tangThem' => 'combo bảo dưỡng 3000km',
            'tamUng' => '30.000.000',
            'tamUngBangChu' => 'Ba mươi triệu đồng',
        ]);
        // Công ty
//        $templateProcessor->setValues([
//            'soHopDong' => '878782321',
//            'ngay' => '08',
//            'thang' => '08',
//            'nam' => '2021',
//            'sale' => 'Trần Bảo Toàn',
//            'salePhone' => '0989 998 998',
//            'guest' => 'Nguyễn Hồng Phương',
//            'diaChi' => 'Long Xuyên, An Giang',
//            'dienThoai' => '0918 988 998',
//            'mst' => '1602037961',
//            'chucVu' => 'Nhân viên',
//            'tenDaiDien' => 'Nguyễn Hồng Phương',
//            'noiDung' => 'Xe Accent 2021 Full Vàng Cát, GHKS08767321, GK0878321. ',
//            'donGia' => '326.000.000',
//            'truocBa' => '26.000.000',
//            'ttruocBa' => '26.000.000',
//            'duongBo' => '1.550.000',
//            'tduongBo' => '1.550.000',
//            'tnns' => '2.500.000',
//            'ttnns' => '2.500.000',
//            'bhtx' => '1.500.000',
//            'tbhtx' => '1.500.000',
//            'bienSo' => '350.000',
//            'tbienso' => '350.000',
//            'dangKiem' => '2.500.000',
//            'tdangKiem' => '2.500.000',
//            'thanhTien' => '326.000.000',
//            'tongCong' => '367.000.000',
//            'phukien' => 'Túi chống sốc, ba lô, thảm lót chân, trải sàn, bình chữa cháy',
//            'tangThem' => 'combo bảo dưỡng 3000km',
//            'tamUng' => '30.000.000',
//            'tamUngBangChu' => 'Ba mươi triệu đồng',
//        ]);
        $pathToSave = 'template/CN_HD_TM_DOWN.docx';
        $templateProcessor->saveAs($pathToSave);

    }

    public function down() {
        $pathToSave = 'template/CN_HD_TM_NEW.docx';
        return response()->download($pathToSave);
    }
}
