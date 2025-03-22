<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HopDong;
use App\KhoV2;
use App\SaleOffV2;
use App\NhatKy;
use App\HistoryHopDong;
use App\TypeCarDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\TemplateProcessor;

class KetoanController extends Controller
{
    //
    public function getKeToan() {
        return view("ketoan.hopdongxe");
    }

    // Bổ sung tính năng cho Kế toán
    public function getXeNhanNo() {
        $type_detail = TypeCarDetail::all()->sortBy('name');
        return view('ketoan.xenhanno', ['typecar' =>  $type_detail]);
    }

    public function getKhoHDList() {
        // old command
        // $result = KhoV2::select('kho_v2.*','h.id as idhopdong','t.name as ten', 'h.lead_check as tpkd', 'ud.surname as saleban','g.name as khach')
        // ->join('type_car_detail as t','kho_v2.id_type_car_detail','=','t.id')
        // ->join('hop_dong as h','h.id_car_kho','=','kho_v2.id')
        // ->join('guest as g','g.id','=','h.id_guest')
        // ->join('users as u','u.id','=','h.id_user_create')
        // ->join('users_detail as ud','ud.id_user','=','u.id')
        // ->where('kho_v2.type','=','HD')
        // ->orderBy('xuatXe', 'asc')->get();

        // $result = KhoV2::select('kho_v2.*','t.name as ten')
        // ->join('type_car_detail as t','kho_v2.id_type_car_detail','=','t.id')
        // ->where('kho_v2.vin','!=',null)  
        // ->get();

        // ----------- end old command
        // ->whereNull('kho_v2.vin')

        $result = KhoV2::select('kho_v2.*','t.name as ten','t.giaVon','h.id as idhopdong','h.hoaHongSale','ud.surname as saleban','g.name as khach')
        ->leftJoin('type_car_detail as t', 'kho_v2.id_type_car_detail', '=', 't.id')
        ->leftJoin('hop_dong as h','kho_v2.id','=','h.id_car_kho')
        ->leftJoin('guest as g','g.id','=','h.id_guest')
        ->leftJoin('users as u','u.id','=','h.id_user_create')
        ->leftJoin('users_detail as ud','ud.id_user','=','u.id')
        ->where('kho_v2.vin','!=',null)
        ->get();


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

    public function getEditXeNhanNo(Request $request) {
        $idKho = $request->id;
        $idHopDong = 0;
        if ($request->idhd)
            $idHopDong = $request->idhd;
        $result = KhoV2::find($idKho);
        $tenXe = $result->typeCarDetail->name;
        $hoaHongSale = 0;
        $hopDong = HopDong::find($idHopDong);
        if($hopDong) {
            $hoaHongSale = $hopDong->hoaHongSale;
        }
        if($result) {
            return response()->json([
                'message' => 'Lấy thông tin xe thành công!',
                'code' => 200,
                'type' => "info",
                'data' => $result,
                'idHopDong' => $idHopDong,
                'hoaHongSale' => $hoaHongSale,
                'tenXe' => $tenXe
            ]);
        } else {
            return response()->json([
                'type' => "error",
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        } 
    }

    public function updateXeNhanNo(Request $request) {
        $isHopDong = false;
        $hhSaleOld = 0;
        if ($request->eidhopdong != 0) {
            $hopDong = HopDong::find($request->eidhopdong); 
            $hhSaleOld = $hopDong->hoaHongSale;
            $hopDong->hoaHongSale = $request->hoaHongSale;
            $hopDong->save();
            if ($hopDong)
                $isHopDong = true;
        }       

        $result = KhoV2::find($request->eid);   
        $temp = KhoV2::find($request->eid);    
        $result->ngayNhanNo = $request->ngayNhanNo;
        $result->ngayRutHoSo = $request->ngayRutHoSo;
        $result->xangLuuKho = $request->xangLuuKho;
        $result->giaTriVay = $request->giaTriVay;
        $result->laiSuatVay = $request->laiSuatVay;
        $result->hhcongdk = $request->hhcongdk;
        $result->giavonbh = $request->giavonbh;
        $result->giavonpk = $request->giavonpk;
        $result->ghiChu = $request->ghiChu;
        $result->save();

        if($result) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Kế toán - Xe nhận nợ";
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->noiDung = "Cập nhật thông tin xe nhận nợ. Xe " . $temp->typeCarDetail->name
            . "; Số khung: " . $temp->vin . "; Số máy: " . $temp->frame 
            . "; Ngày nhận nợ từ ". $temp->ngayNhanNo ." thành " . $request->ngayNhanNo
            . "; Ngày rút hồ sơ từ ". $temp->ngayRutHoSo ." thành " . $request->ngayRutHoSo
            . "; Xăng lưu kho từ ". $temp->xangLuuKho ." thành " .  $request->xangLuuKho
            . "; Giá trị vay từ ". $temp->giaTriVay ."% thành " . $request->giaTriVay
            . "%; Lãi suất vay từ ". $temp->laiSuatVay ."% thành " . $request->laiSuatVay . "%"
            . "; Hoa hồng sale từ ". $hhSaleOld  ." thành ".$request->hoaHongSale;
            $nhatKy->save();

            $his = new HistoryHopDong();
            $his->idDeNghi = $request->eidhopdong;
            $his->id_user = Auth::user()->id;
            $his->ngay = Date("H:m:s d-m-Y");
            $his->noiDung = "Cập nhật thông tin xe nhận nợ. Xe " . $temp->typeCarDetail->name
            . "; Số khung: " . $temp->vin . "; Số máy: " . $temp->frame 
            . "; Ngày nhận nợ từ ". $temp->ngayNhanNo ." thành " . $request->ngayNhanNo
            . "; Ngày rút hồ sơ từ ". $temp->ngayRutHoSo ." thành " . $request->ngayRutHoSo
            . "; Xăng lưu kho từ ". $temp->xangLuuKho ." thành " .  $request->xangLuuKho
            . "; Giá trị vay từ ". $temp->giaTriVay ."% thành " . $request->giaTriVay
            . "%; Lãi suất vay từ ". $temp->laiSuatVay ."% thành " . $request->laiSuatVay . "%"
            . "; Hoa hồng sale từ ". $hhSaleOld  ." thành ".$request->hoaHongSale;
            $his->ghiChu = "";
            $his->save();

            return response()->json([
                'message' => 'Cập nhật xe nhận nợ: Thành công; Hoa hồng sale: ' . (($isHopDong) ? "Thành công" : "Thất bại"),
                'code' => 200,
                'type' => "success",
            ]);
        } else {
            return response()->json([
                'type' => "error",
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }      
    }
    // -------------------

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
        // $magiamgia = $hd->magiamgia;
        $khoXe = KhoV2::find($hd->id_car_kho);
        $ngayGiaoXe = \HelpFunction::revertDate($khoXe->ngayGiaoXe);
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
                $phukienall = "";
                $stt = "";
                $noidung = "";
                $sl = "";
                $k = 1;
                $tong = "";
                $tongpkpay = 0;
                foreach($package as $row) {
                    if ($row->type == 'pay') {
                        $saleOff = SaleOffV2::select("*")->where([
                            ['id_hd','=',$id],
                            ['id_bh_pk_package','=',$row->id]
                        ])->first();
                        $sumpkban += ($row->cost - ($row->cost*$saleOff->giamGia/100));
                        $phukienall .= $row->name . ", ";
                        $tongpkpay++;
                    }
                    if ($row->type == 'cost' && $row->cost_tang == false) {
                        $sumchiphi += $row->cost;
                    }
                    if ($row->type == 'free' && $row->free_kem == true) {
                        $stt .= $k . '<w:br/>';
                        $noidung .=  $row->name . '<w:br/>';                  
                        $sl .= '1<w:br/>';
                        $k++;
                    }

                    if ($row->type == 'free' && $row->free_kem == false) {
                        $tongpkpay++;
                        $phukienall .= $row->name . ", ";
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
                    'mst' => $sale->guest->mst,
                    'cccd' => $sale->guest->cmnd,
                    'ngayCap' => $sale->guest->ngayCap,
                    'noiCap' => $sale->guest->noiCap,
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
                    'tong' => number_format($sumchiphi + $sumpkban + $giaXe),
                    'phukienall' => $phukienall,
                    'stt' => $stt,
                    'noiDung' => $noidung,
                    'sl' => $sl,
                    'tongpk' => ($k - 1),
                    'tongpkpay' => $tongpkpay,
                    'ngaygiaoxe' => $ngayGiaoXe,
                    'payGiamGia' => number_format($sumpkban)
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
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->noiDung = "In biên bản bàn giao xe hợp đồng số " . $hd->code;
            $nhatKy->save();

            $his = new HistoryHopDong();
            $his->idDeNghi = $id;
            $his->id_user = Auth::user()->id;
            $his->ngay = Date("H:m:s d-m-Y");
            $his->noiDung = "In biên bản bàn giao xe";
            $his->ghiChu = "";
            $his->save();

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
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->noiDung = "In quyết toán xe hợp đồng số " . $hd->code;
            $nhatKy->save();

            $his = new HistoryHopDong();
            $his->idDeNghi = $id;
            $his->id_user = Auth::user()->id;
            $his->ngay = Date("H:m:s d-m-Y");
            $his->noiDung = "In quyết toán xe";
            $his->ghiChu = "";
            $his->save();

            return response()->download($pathToSave,$outhd . '.docx',$headers);
        // } else {
        //     echo "<script>alert('Chưa xuất xe, chưa thể in quyết toán');</script>";
        // }        
    }

    public function getBaoCaoHopDong(){
        return view('khoxe.baocaohopdong');
        // return view('ketoan.hopdongchitiet');
    }
}
