<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $status;

    public function __construct($name,$status)
    {
        $this->name = $name;
        $this->status = $status;
    }

    public function build()
    {
        return $this->subject('Order Status')->view('emails.order-status-email')
                    ->with([
                        'name' => $this->name,
                        'status' => $this->status,
                    ]);
    }
}
