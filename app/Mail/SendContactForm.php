<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendContactForm extends Mailable
{
    use Queueable, SerializesModels;

    protected $contactCompany;
    protected $contactEmail;
    protected $contactMessage;
    protected $contactName;

    /**
     * Create a new message instance.
     *
     * @return void
     *
     * @param $contactCompany   string
     * @param $contactEmail     string
     * @param $contactMessage   string
     * @param $contactName      string
     */
    public function __construct($contactCompany, $contactEmail, $contactMessage, $contactName)
    {
        $this->contactCompany = $contactCompany;
        $this->contactEmail   = $contactEmail;
        $this->contactMessage = $contactMessage;
        $this->contactName    = $contactName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contact')
                    ->with([
                        'contactCompany' => $this->contactCompany,
                        'contactEmail'   => $this->contactEmail,
                        'contactMessage' => $this->contactMessage,
                        'contactName'    => $this->contactName
                    ]);
    }
}
