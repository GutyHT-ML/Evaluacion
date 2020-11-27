<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Register extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($USER, $EMAIL)
    {
        $this->user = $USER;
        $this->email = $EMAIL;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('19170050@uttcampus.edu.mx')->markdown('register',['email'=>$this->email, 'user'=>$this->user]);
    }
}
