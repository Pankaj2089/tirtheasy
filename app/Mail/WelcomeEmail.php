<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $record= '';
    public function __construct($record)
    {
        $this->record = $record;
    }


    public function build()
    {
        return $this->view('emails.welcome_mail')
                    ->subject("Welcome to TirthEasy,  Your Pilgrimage Booking Made Easy!");
    }
}