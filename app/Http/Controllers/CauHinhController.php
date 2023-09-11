<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CauHinhController extends Controller
{
    //
    public function viewList(){
        return view('cauhinh.cauhinh');
    }
    public function getAjax() {
        $jsonString = file_get_contents('upload/cauhinh/app.json');
        $data = json_decode($jsonString, true);   
        return response()->json([
            'type' => 'success',
            'message' => 'Đã tải',
            'code' => 200,
            'data' => $data
        ]);
    }

    public function saveConfig(Request $request) {
        $data["hinhNen"] = $request->hinhNen;      
        $data["thongBao"] = $request->thongBao;        
        $data["emailPhep"] = $request->emailPhep;        
        $data["emailNhienLieu"] = $request->emailNhienLieu;        
        $data["emailCCDC"] = $request->emailCCDC;   
        $data["emailCapHoa"] = $request->emailCapHoa;        
        $data["emailDuyetXe"] = $request->emailDuyetXe;        
        $data["emailTraXe"] = $request->emailTraXe; 
        $data["capNhatThongTin"] = $request->capNhatThongTin;       
        $data["mauThongBao"] = $request->mauThongBao;       
        $data["loaiThongBao"] = $request->loaiThongBao;
        $data["maxRecord"] = $request->maxRecord;
        $data["maxRecordApply"] = $request->maxRecordApply;
        $data["vaoSang"] = $request->vaoSang;       
        $data["raSang"] = $request->raSang;       
        $data["vaoChieu"] = $request->vaoChieu;       
        $data["raChieu"] = $request->raChieu;              

        $newJsonString = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents('upload/cauhinh/app.json', $newJsonString);
        return response()->json([
            'type' => 'success',
            'message' => 'Đã lưu cấu hình',
            'code' => 200,
            'data' => $data
        ]);
    }
}
