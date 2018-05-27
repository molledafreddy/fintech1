<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewCustomerAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $token;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $token)// llama el contructor de esa clase, tu vas recibir un vector llamada data
    {
        //dentro del contructor
        $this->data=$data;
        $this->token=$token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.newcustomeradmin')->with(['token',$this->token])->from('miguel.jordan@ademia.io');
    }
}
