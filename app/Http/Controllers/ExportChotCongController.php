<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;
use App\NhatKy;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;


class ExportChotCongController extends Controller implements FromCollection, WithHeadings
{
    use Exportable;
    private $from;
    private $to;
    private $baoCao;
    private $u;

    public function __construct($from,$to,$baoCao,$u){
        $this->from = $from;
        $this->to = $to;
        $this->baoCao = $baoCao;
        $this->u = $u;
    }

    public function collection()
    {
        $hds = [];
        // $hds[] = array(
        //     '0' => $i++,
        //     '1' => \HelpFunction::getDateRevertCreatedAt($row->created_at),
        //     '2' => $row->user->userDetail->surname,
        //     '3' => $_sale,
        //     '4' => $row->hoTen,
        //     '5' => $_loaibg,
        //     '6' => "BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at),
        //     '7' => $_doanhthu,
        //     '8' => $_chiphitang,
        //     '9' => $_chietKhauCost,
        //     '10' => $_thucThu,
        //     '11' => \HelpFunction::revertDate($row->ngayThu),
        // );

        return (collect($hds));
    }

    public function headings(): array
    {
        return [
            'STT',
            'Ngày tạo',
            'Người tạo',
            'Sale',
            'KH',
            'Loại báo giá',
            'Số báo giá',
            'Doanh thu',
            'Tặng',
            'Chiết khấu',
            'Thực tế',
            'KT xác nhận',
        ];
    }
    
    public function export(){
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "NHÂN SỰ - QUẢN LÝ CHỐT CÔNG";
        $nhatKy->noiDung = "Xuất excel chốt công";
        $nhatKy->save();
        return Excel::download(new ExportChotCongController(), 'chotcong.xlsx');
    }
}
