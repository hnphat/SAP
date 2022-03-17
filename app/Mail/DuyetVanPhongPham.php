<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DuyetVanPhongPham extends Mailable
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
        return $this->subject('[THÔNG BÁO] Bạn nhận được 01 yêu cầu cấp công cụ dụng cụ')
                    ->markdown('emails.duyetvanphongpham')->with([
                        'nguoiDuyet' => 'Phòng hành chính',
                        'nguoiYeuCau' => $this->arr_data[0],
                        'maPhieu' => $this->arr_data[1],
                        'noiDung' => $this->arr_data[2],
                    ]);
    }
}
