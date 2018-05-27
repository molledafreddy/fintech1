<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUserWelcome extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    
    public $name;
    public $customer;
    public $url;
    
    public function __construct($name, $customer, $url)
    {
        $this->name = $name;
        $this->customer = $customer;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from(['address' => 'noreply@fintech.com', 'name' => 'Fintech'])
                    ->subject('Confirmación uso Servício')
                    ->markdown('emails.user.newuserwelcome');
    }
}
