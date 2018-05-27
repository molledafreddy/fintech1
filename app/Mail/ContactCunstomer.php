<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactCunstomer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $subject;
    public $content;
    public function __construct($user, $subject, $content)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.customer.contactcustomer')
        ->from([
              "address" => "contacto@fintech.com",
              "name" => "Notificacion observaciones",
              
          ]);
    }
}
