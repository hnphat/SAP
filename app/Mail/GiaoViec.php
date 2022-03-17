<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GiaoViec extends Mailable
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
        return $this->subject('[THÔNG BÁO] Bạn nhận được 01 công việc mới')
                    ->markdown('emails.giaoviec')->with([
                        'nguoiNhan' => $this->arr_data[0],
                        'nguoiGiao' => $this->arr_data[1],
                        'ngayBatDau' => $this->arr_data[2],
                        'ngayKetThuc' => $this->arr_data[3],
                        'yeuCau' => $this->arr_data[4],
                    ]);
    }
}
