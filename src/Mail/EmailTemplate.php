<?php

namespace TwinDots\EmailService\Mail;

use \Illuminate\Bus\Queueable;
use \Illuminate\Contracts\Queue\ShouldQueue;
use \Illuminate\Mail\Mailable;
use \Illuminate\Queue\SerializesModels;

class EmailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    public $subject;

    /**
     * Email content
     * @var string|null
     */
    public string|null $content;

    /**
     * Email attachments
     * @var array
     */
    public array $files;

    /**
     * Reply to
     * @var string
     */
    public string $replyToEmail;

    /**
     * EmailTemplate constructor.
     * @param string $subject
     * @param string|null $content
     * @param array $files
     * @param string $replyToEmail
     */
    public function __construct(
        string $subject,
        string|null $content = null,
        array $files = [],
        string $replyToEmail = '',
    )
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->files = $files;
        $this->replyToEmail = $replyToEmail;
    }

    /**
     * Build email
     * @return $this
     */
    public function build(): static
    {
        if ($this->subject)
            $this->subject($this->subject);

        if( $this->replyToEmail )
            $this->replyTo( $this->replyToEmail );

        $this->view(config('email_service.email_template'));

        if (!empty($this->files))
            foreach ($this->files as $name => $file) {
                $this->attach($file, ['as' => $name]);
            }

        return $this;
    }
}
