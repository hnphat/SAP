<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DuyetCapXangTBP extends Mailable
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
        return $this->subject('[THÔNG BÁO] Bạn nhận được 01 yêu cầu cấp nhiên liệu')
                    ->markdown('emails.duyetcapxangtbp')->with([
                        'nguoiDuyet' => $this->arr_data[0],
                        'nguoiYeuCau' => $this->arr_data[1],
                        'code' => $this->arr_data[2],
                        'ngayDangKy' => $this->arr_data[3],
                        'xeDangKy' => $this->arr_data[4],
                        'lyDo' => $this->arr_data[5],
                        'nhienLieu' => $this->arr_data[6],
                        'soLit' => $this->arr_data[7],
                        'khach' => $this->arr_data[8],
                        'ghiChu' => $this->arr_data[9],
                    ]);
    }
}
