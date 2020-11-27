<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Confirm extends Mailable
{
    use Queueable, SerializesModels;
    public $file;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($FILE)
    {
        $this->file = $FILE;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('19170050@uttcampus.edu.mx')->markdown('confirm',['file'=>$this->file]);
    }
}
