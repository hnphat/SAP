<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TraXe extends Mailable
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
        return $this->subject('[THÔNG BÁO] Bạn nhận được 01 yêu cầu trả xe demo')
                    ->markdown('emails.traxe')->with([
                        'nguoiDuyet' => $this->arr_data[0],
                        'nguoiYeuCau' => $this->arr_data[1],
                        'ngayDi' => $this->arr_data[2],
                        'ngayTra' => $this->arr_data[3],
                        'xeDangKy' => $this->arr_data[4],
                        'km' => $this->arr_data[5],
                        'kmXang' => $this->arr_data[6],
                        'status' => $this->arr_data[7],
                        'hoSo' => $this->arr_data[8],
                    ]);
    }
}
