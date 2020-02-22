<?php

namespace TwinDots\EmailService\Services;

use \Illuminate\Support\Facades\Mail;
use TwinDots\EmailService\Mail\EmailTemplate;

class EmailService {

   /**
    * Recipient email
    * @var array
    */
   protected $email;
 
   /**
    * Email subject
    * @var String
    */
   protected $subject;

   /**
    * Email body
    * @var text
    */
   protected $body;

   /**
    * Email attachments
    * @var array
    */
   protected $attachments;

   /**
     * Create a new EmailService instance.
     *
     * @param  array  $email
     * @param  String  $subject
     * @param  text  $body
     * @return void
     */
   public function __construct( $email = null, $subject = null, $body = null ){

      $this->email( $email );
      $this->subject( $subject );
      $this->body( $body );

   }

   /**
     * Set the recipients emails.
     *
     * @param  array  $email  
     * @return void
     */
   public function email( $email ){
      $this->email = $email;
      return $this;
   } 

   /**
     * Set the email subject.
     * 
     * @param  String  $subject 
     * @return void
     */
   public function subject( $subject ){
      $this->subject = $subject;
      return $this;
   }

   /**
     * Set the email body.
     * 
     * @param  text  $body
     * @return void
     */
   public function body( $body ){
      $this->body = $body;
      return $this;
   }

   /**
     * Set the email attachments.
     *
     * @param  array  $attachments 
     * @return void
     */
   public function attach( $attachments ){
      $this->attachments = $attachments;
      return $this;
   }

   /**
     * Send the email.
     *
     * @return array
     */
   public function send(){

      try {

         Mail::to( $this->email )
               ->send( 
                  new EmailTemplate( 
                     $this->subject, 
                     $this->body,
                     $this->attachments
                  ) 
               );

         return [
            'sent' => true, 
         ];

      } catch (\Exception $e) {
         
         return [
            'sent' => false,
            'messasge' => $e->getMessage() 
         ];

      }
   } 
}