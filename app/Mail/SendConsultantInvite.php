<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendConsultantInvite extends Mailable
{
    public $data=[];
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $salutation, $first_name, $last_name, $shareLink)
    {

        $this->data=[
          'email'=>$email,
          'salutation'=>$salutation,
          'first_name'=>$first_name,
          'last_name'=>$last_name,
          'share_link'=>$shareLink
        ];

    }

    /**
     * Build the message.
     *

     * @return $this
     */
    public function build()
    {

        return $this->view('emails.it_consultant_send_invite_to_user', $this->data );

    }
}
