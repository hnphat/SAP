<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GroupGet extends Mailable
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
        return $this->subject('[THÔNG BÁO] Nhóm của bạn nhận được khách hàng cần chăm sóc')
                    ->markdown('emails.groupget')->with([
                        'nhom' => $this->arr_data[0],
                        'nguoiDuyet' => $this->arr_data[1],
                        'hoTen' => $this->arr_data[2],
                        'dienThoai' => $this->arr_data[3],
                        'yeuCau' => $this->arr_data[4],
                    ]);
    }
}
