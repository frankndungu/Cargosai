<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $verificationUrl;

    public function __construct($token)
    {
        $this->token = $token;
        $this->verificationUrl = url("/api/email/verify/{$token}");
    }

    public function build()
    {
        return $this->view('emails.confirm')
                    ->subject('Please Verify Your Email Address');
    }
}