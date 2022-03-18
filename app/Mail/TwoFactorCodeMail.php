<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFactorCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $code;
    public $subject;
    public $data = [];

    public function __construct($code, $subject = 'Two-factor code')
    {
        $this->code = $code;
        $this->subject = $subject;
        $this->data = [
            'code' => $this->code
        ];

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('abc@gmail.com')->markdown('emails.two_factor_code', $this->data);
    }

}
