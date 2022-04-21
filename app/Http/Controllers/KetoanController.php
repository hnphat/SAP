<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HopDong;
use App\KhoV2;
use App\NhatKy;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\TemplateProcessor;

class KetoanController extends Controller
{
    //
    public function getKeToan() {
        return view("ketoan.hopdongxe");
    }

    public function getDanhSachHopDong() {
        $hdWait = "";
        $code = "";
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('ketoan'))
            $result = HopDong::select('*')
            ->where([
                ['lead_check','=',true],
                ['lead_check_cancel', '=', false]
            ])
            ->orderby('id','desc')->get();
            // $result = HopDong::select('*')
            // ->where([
            //     ['lead_check','=',true],
            //     ['lead_check_cancel', '=', false],
            //     ['hdWait', '=', false]
            // ])
            // ->orderby('id','desc')->get();
        if($result) {
                echo "<option value='0'>Chọn</option>";
            foreach($result as $row){
                if($row->hdWait == true) 
                    $hdWait = "(Hợp đồng chờ)";
                else
                    $hdWait = "";

                if($row->code == 0) 
                    $code = "";
                else
                    $code = "[Số hợp đồng: ".$row->code.".".$row->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($row->created_at)."/HĐMB-PA]";

                    if($row->lead_check_cancel	== true) 
                    echo "<option value='".$row->id."'>".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."]"."</option>";
                elseif ($row->requestCheck == false)
                    echo "<option value='".$row->id."'>".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."]"."</option>";
                elseif($row->requestCheck == true && $row->admin_check == false) 
                    echo "<option value='".$row->id."'>".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."]"."</option>";
                elseif($row->requestCheck == true && $row->admin_check == true && $row->lead_check == false) 
                    echo "<option value='".$row->id."'>".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."]"."</option>";
                elseif($row->requestCheck == true && $row->admin_check == true && $row->lead_check == true) 
                    echo "<option value='".$row->id."'>".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."]"."</option>";
            }
        } else {
            echo "<option value='0'>Không tìm thấy</option>";
        }
    }

    public function inBienBan($id) {
        //----- Đã xuất xe mới in được biên bản bàn giao
        $hd = HopDong::find($id);
        $khoXe = KhoV2::find($hd->id_car_kho);
        if($khoXe->xuatXe == true) {
        //-----        
            $outhd = "";
            $templateProcessor = new TemplateProcessor('template/BIENBANBANGIAO.docx');
                $sale = HopDong::find($id);
                $kho = KhoV2::find($sale->id_car_kho);
                $year = $kho->year;
                $soHopDong = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
                $car_detail = $sale->carSale;
                $car = $sale->carSale;
                $giaXe = $sale->giaXe;
                $tenXe = $car_detail->name;
                // $tenXe = $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD';
                $outhd = 'Biên bản bàn giao xe - KH ' . $sale->guest->name;
                $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);

                // Exe phụ kiện bán và free
                $package = $sale->package;

                $sumpkban = 0;
                $sumchiphi = 0;

                foreach($package as $row) {
                    if ($row->type == 'pay') {
                        $sumpkban += $row->cost;
                    }
                    if ($row->type == 'cost' && $row->cost_tang == false) {
                        $sumchiphi += $row->cost;
                    }
                }

                $templateProcessor->setValues([
                    'soHopDong' => $soHopDong,
                    'ngayhd' => $arrdate[2],
                    'thanghd' => $arrdate[1],
                    'namhd' => $arrdate[0],
                    'ngay' => Date('d'),
                    'thang' => Date('m'),
                    'nam' => Date('Y'),
                    'sale' => $sale->user->userDetail->surname,
                    'guest' => $sale->guest->name,
                    'daiDien' => $sale->guest->daiDien,
                    'diaChi' => $sale->guest->address,
                    'phone' => $sale->guest->phone,
                    'carname' => $tenXe,
                    'giaXe' => number_format($giaXe),
                    'year' => $year,
                    'seat' => $car->seat,
                    'color' => $sale->mau,
                    'vin' => $kho->vin,
                    'frame' => $kho->frame,
                    'cost' => number_format($sumchiphi),
                    'pay' => number_format($sumpkban),
                    'tong' => number_format($sumchiphi + $sumpkban + $giaXe)
                ]);

            $pathToSave = 'template/BIENBANBANGIAODOWN.docx';
            $templateProcessor->saveAs($pathToSave);
            $headers = array(
                'Content-Type: application/docx',
            );
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Kế toán - Hợp đồng xe";
            $nhatKy->noiDung = "In biên bản bàn giao xe hợp đồng số " . $hd->code;
            $nhatKy->save();
            return response()->download($pathToSave,$outhd . '.docx',$headers);
        } else {
            echo "<script>alert('Chưa xuất xe, chưa thể in biên bản bàn giao xe');</script>";
        }        
    }

    public function inQuyetToan($id) {
        //----- Đã xuất xe mới in được quyết toán
        $hd = HopDong::find($id);
        $khoXe = KhoV2::find($hd->id_car_kho);
        // if($khoXe->xuatXe == true) {
        //-----        
            $outhd = "";
            $templateProcessor = new TemplateProcessor('template/QUYETTOAN.docx');
                $sale = HopDong::find($id);
                $kho = KhoV2::find($sale->id_car_kho);
                $year = $kho->year;
                $soHopDong = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
                $car_detail = $sale->carSale;
                $car = $sale->carSale;
                $giaXe = $sale->giaXe;
                $tenXe = $car_detail->name;
                // $tenXe = $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD';
                $outhd = 'Quyết toán - KH ' . $sale->guest->name;
                $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);

                // Exe phụ kiện bán và free
                $package = $sale->package;

                $sumpkban = 0;
                $sumchiphi = 0;

                foreach($package as $row) {
                    if ($row->type == 'pay') {
                        $sumpkban += $row->cost;
                    }
                    if ($row->type == 'cost') {
                        $sumchiphi += $row->cost;
                    }
                }

                $templateProcessor->setValues([
                    'soHopDong' => $soHopDong,
                    'ngayhd' => $arrdate[2],
                    'thanghd' => $arrdate[1],
                    'namhd' => $arrdate[0],
                    'ngay' => Date('d'),
                    'thang' => Date('m'),
                    'nam' => Date('Y'),
                    'sale' => $sale->user->userDetail->surname,
                    'guest' => $sale->guest->name,
                    'daiDien' => $sale->guest->daiDien,
                    'diaChi' => $sale->guest->address,
                    'phone' => $sale->guest->phone,
                    'carname' => $tenXe,
                    'giaXe' => number_format($giaXe),
                    'year' => $year,
                    'seat' => $car->seat,
                    'color' => $sale->mau,
                    'vin' => $kho->vin,
                    'frame' => $kho->frame,
                    'cost' => number_format($sumchiphi),
                    'pay' => number_format($sumpkban),
                    'tong' => number_format($sumchiphi + $sumpkban + $giaXe)
                ]);

            $pathToSave = 'template/QUYETTOANDOWN.docx';
            $templateProcessor->saveAs($pathToSave);
            $headers = array(
                'Content-Type: application/docx',
            );
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Kế toán - Hợp đồng xe";
            $nhatKy->noiDung = "In quyết toán xe hợp đồng số " . $hd->code;
            $nhatKy->save();
            return response()->download($pathToSave,$outhd . '.docx',$headers);
        // } else {
        //     echo "<script>alert('Chưa xuất xe, chưa thể in quyết toán');</script>";
        // }        
    }

    public function getBaoCaoHopDong(){
        return view('ketoan.hopdongchitiet');
    }
}
