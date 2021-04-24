<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailPreRegister extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $businessType;

    /**
     * @var string
     */
    public $businessCompany;

    /**
     * @var string
     */
    public $city;

    /**
     * SendEmailPreRegister constructor.
     * @param string $name
     * @param string $email
     * @param string $phone
     * @param string $businessType
     * @param string $businessCompany
     * @param string $city
     */
    public function __construct(
        string $name,
        string $email,
        string $phone,
        string $businessType,
        string $businessCompany,
        string $city
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->businessType = $businessType;
        $this->businessCompany = $businessCompany;
        $this->city = $city;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Nuevo pre registro');
        return $this->view('admin.mail.pre-register');
    }
}
