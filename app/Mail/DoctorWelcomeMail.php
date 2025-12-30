<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DoctorWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $doctorName;
    public $email;
    public $plainPassword;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $plainPassword)
    {
        $this->doctorName = $user->name;
        $this->email = $user->email;
        $this->plainPassword = $plainPassword;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your DoctorA2Z Account Credentials')
                    ->view('emails.doctor_welcome')
                    ->with([
                        'doctorName'   => $this->doctorName,
                        'email'        => $this->email,
                        'plainPassword'=> $this->plainPassword,
                    ]);
    }
}
