<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;
use App\NhomSP;
use App\DanhMucSP;
use App\NhapSP;
use App\XuatSP;
use App\PhieuXuat;
use App\NhatKy;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;


class ExportTonKhoController extends Controller implements FromCollection, WithHeadings
{
    use Exportable;
    private $from;
    private $to;

    public function __construct($from,$to){
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        $hds = [];
        // $_from = \HelpFunction::revertDate($this->from);
        // $_to = \HelpFunction::revertDate($this->to);
        //-------------------------------
        // $hds[] = array(
        //     '0' => "1",
        //     '1' => "2",
        //     '2' => "3",
        //     '3' => "4",
        //     '4' => "5",
        //     '5' => "6",
        //     '6' => "7",            
        // );
        //-------------------------------
        $listNhom = NhomSP::all();
        foreach($listNhom as $nhom) {
                $listDM = DanhMucSP::where('id_nhom',$nhom->id)->get();
                $i = 1;
                foreach($listDM as $row) {
                    $newPN = NhapSP::select('id_danhmuc', DB::raw('sum(soLuong) as soLuong'))           
                    ->groupBy('id_danhmuc')
                    ->having('id_danhmuc', $row->id)
                    ->first(); 
                    $newPX = XuatSP::where('id_danhmuc',$row->id)->get();
                    $soLuongNhap = (isset($newPN) ? $newPN->soLuong : 0);
                    $soLuongXuat = 0;
                    foreach($newPX as $item) {
                        $px = PhieuXuat::find($item->id_xuat);
                        if ($px->duyet == true && $px->nhan == true) {
                            $soLuongXuat += $item->soLuong;
                        }
                    }
                    $hds[] = array(
                        '0' => $i++,
                        '1' => $row->tenSanPham,
                        '2' => $row->donViTinh,
                        '3' => ($row->isCongCu ? "Công cụ" : "Dụng cụ"),
                        '4' => $row->moTa,
                        '5' => $soLuongNhap != 0 ? $soLuongNhap : "",
                        '6' => $soLuongXuat != 0 ? $soLuongXuat : "",
                        '7' => (($soLuongNhap - $soLuongXuat) != 0 ? ($soLuongNhap - $soLuongXuat) : ""),                   
                    );
                }         
        }        
        return (collect($hds));
    }

    public function headings(): array
    {
        return [
            'STT',
            'Công cụ/Dụng cụ',
            'Loại',
            'Đơn vị tính',
            'Mô tả',
            'Số lượng nhập',
            'Số lượng xuất',
            'Tồn thực tế',          
        ];
    }
    
    public function export(){
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "HÀNH CHÍNH - BÁO CÁO TỒN KHO";
        $nhatKy->noiDung = "Xuất excel báo cáo tồn kho";
        $nhatKy->save();
        return Excel::download(new ExportTonKhoController(), 'tonkho_'.Date("d-m-Y").'.xlsx');
    }
}
