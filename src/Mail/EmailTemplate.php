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
    * Email body
    * @var text
    */
    public $content;

    /**
    * Email Subject
    * @var String
    */
    public $subject;

    /**
    * Email attachments
    * @var array
    */
    public $files;

    /**
     * Create a new message instance.
     *
     * @param  String  $subject
     * @param  text  $content
     * @param  array  $files
     * @return void
     */
    public function __construct( $subject = null, $content = null, $files = [] )
    {
      $this->content = $content;
      $this->subject = $subject;
      $this->files = $files;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      if( $this->subject )
         $this->subject( $this->subject );

      $this->view( config('email_service.email_template') );

      if( !empty( $this->files ) )
         foreach ($this->files as $name => $file) {
            $this->attach( $file, [ 'as' => $name ]);
         }
           
      return $this;
    }
}
