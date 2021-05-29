<?php

namespace TwinDots\EmailService\Services;

use Exception;
use \Illuminate\Support\Facades\Mail;
use TwinDots\EmailService\Mail\EmailTemplate;

class EmailService
{

    /**
     * Recipient email
     * @var array|string
     */
    protected string|array $email = '';

    /**
     * CC
     * @var array|string
     */
    protected string|array $cc = '';

    /**
     * BCC
     * @var array|string
     */
    protected string|array $bcc = '';

    /**
     * Reply to email
     * @var string
     */
    protected string $replyTo = '';

    /**
     * Email subject
     * @var string
     */
    protected string $subject = '';

    /**
     * Email body
     * @var string
     */
    protected string $body = '';

    /**
     * Email attachments
     * @var array
     */
    protected array $attachments = [];

    /**
     * EmailService constructor.
     * @param array|string|null $email
     * @param string|null $subject
     * @param string|null $body
     */
    public function __construct($email = null, $subject = null, $body = null)
    {
        if( $email )
            $this->email($email);

        if( $subject )
            $this->subject($subject);

        if( $body )
            $this->body($body);
    }

    /**
     * Set the recipients emails.
     * @param array|string $email
     * @return EmailService
     */
    public function email(array|string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Set the cc emails.
     * @param array|string $cc
     * @return EmailService
     */
    public function cc(array|string $cc): static
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * Set the bcc emails.
     * @param array|string $bcc
     * @return EmailService
     */
    public function bcc(array|string $bcc): static
    {
        $this->bcc = $bcc;
        return $this;
    }

    /**
     * Set the reply to email.
     * @param string $replyTo
     * @return EmailService
     */
    public function replyTo(string $replyTo): static
    {
        $this->replyTo = $replyTo;
        return $this;
    }

    /**
     * Set the email subject.
     * @param string $subject
     * @return EmailService
     */
    public function subject(string $subject): static
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Set the email body.
     * @param string $body
     * @return EmailService
     */
    public function body(string $body): static
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Set the email attachments.
     * @param array['attachment_name' => 'attachment_path'] $attachments
     * @return EmailService
     */
    public function attach(array $attachments): static
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * Send email
     * @return array
     */
    public function send(): array
    {
        try {

            $mail = Mail::to($this->email);

            if( $this->cc )
                $mail = $mail->cc($this->cc);

            if( $this->bcc )
                $mail = $mail->bcc($this->bcc);

            $mail->send(
                new EmailTemplate(
                    $this->subject,
                    $this->body,
                    $this->attachments,
                    $this->replyTo
                )
            );

            return [
                'sent' => true,
            ];

        } catch (Exception $e) {

            return [
                'sent' => false,
                'message' => $e->getMessage()
            ];

        }
    }
}