<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $issues;

    /**
     * SendEmail constructor.
     * @param string $name
     * @param string $text
     * @param string $issues
     */
    public function __construct(
        string $name,
        string $text,
        string $issues
    ) {
        $this->name = $name;
        $this->text = $text;
        $this->issues = $issues;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->from('no-reply@thenuap.com');
        $this->subject($this->issues);
        return $this->view('admin.mail.general');
    }
}
