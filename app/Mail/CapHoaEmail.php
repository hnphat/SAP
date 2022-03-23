<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CapHoaEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->arr_data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[THÔNG BÁO] Bạn nhận được 01 đề nghị cấp hoa')
                    ->markdown('emails.caphoa')->with([
                        'nguoiDuyet' => $this->arr_data[0],
                        'nguoiYeuCau' => $this->arr_data[1],
                        'khachHang' => $this->arr_data[2],
                        'dongXe' => $this->arr_data[3],
                        'num' => $this->arr_data[4],
                        'gioGiaoXe' => $this->arr_data[5],
                        'ngayGiaoXe' => $this->arr_data[6],
                        'ghiChu' => $this->arr_data[7]
                    ]);
    }
}
