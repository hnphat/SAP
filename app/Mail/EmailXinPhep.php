<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailXinPhep extends Mailable
{
    use Queueable, SerializesModels;

    public $arr_data;
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
        return $this->subject('[THÔNG BÁO] Bạn nhận được 01 đơn xin phép mới')
                    ->markdown('emails.xinphep')->with([
                        'nhanVien' => $this->arr_data[0],
                        'ngayXin' => $this->arr_data[1],
                        'loaiPhep' => $this->arr_data[2],
                        'lyDo' => $this->arr_data[3],
                        'nguoiDuyet' => $this->arr_data[4],
                        'buoi' => $this->arr_data[5],
                    ]);
    }
}
