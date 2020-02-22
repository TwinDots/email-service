<?php

namespace TwinDots\EmailService\Services;

class EmailShortCodes {
    
   /**
    * Short codes list
    * @var array
    */
   protected $shortcodes;
   

   /**
    * Short codes group
    * @var string
    */
   protected $group; 


   /**
    * Body to be compiled
    * @var text
    */
   protected $body; 


   /**
    * Objects used in compile
    * @var array
    */
   protected $objects;


   /**
    * Create a new instance.
    *
    * @param  String  $group
    * @return void
    */
   public function __construct( $group = '' ){
      $this->group( $group );
   }


   /**
    * Compile given body using a shortcodes list and a set of given objects.
    * 
    * @return Text
    */
   public function compile(){
      $compiled = $this->body;

      foreach ( $this->shortcodes as $code => $options) {

         if( !isset($options['type']) )
            continue;

         // Compile variable
         if( $options['type'] == 'variable' ){

            if( isset($options['object']) 
                  && isset($options['param'])
                   && isset( $this->objects[ $options['object'] ] ) 
               )
               $parameter = $this->objects[ $options['object'] ]->{ $options['param'] } 
                  ? $this->objects[ $options['object'] ]->{ $options['param'] }
                   : '' ;

         }

         // Compile function
         if( $options['type'] == 'function' ){
            
            if( isset($options['object']) 
                  && isset($options['param'])
                   && isset( $this->objects[ $options['object'] ] ) 
               )
               $parameter = method_exists( $this->objects[ $options['object']], $options['param'] )  
                  ? call_user_func( [ $this->objects[ $options['object']], $options['param']] )
                   : '' ;
         }

         // Compile view
         if( $options['type'] == 'view' ){
            
            if( isset($options['object']) 
                  && isset($options['param']) )  
               $parameter = view()->exists( $options['object'] ) 
                  ? view( $options['object'] )
                     ->with( $this->objects )
                     ->render()
                   : '';
         }

         if( isset($parameter) )
            $compiled = str_replace('{'.$code.'}', $parameter, $compiled);
      }

      return $compiled;
   }


   /**
    * Set the body.
    * 
    * @param  Text  $body
    */
   public function body( $body = '' ){
      $this->body = $body;
      return $this;
   }


   /**
    * Set the objects.
    * 
    * @param  Array  $objects
    */
   public function objects( $objects = [] ){
      $this->objects = $objects;
      return $this;
   }


   /**
    * Set the group.
    * 
    * @param  String  $group
    */
   public function group( $group = '' ){
      $this->shortcodes = $this->getGroup($group);
      return $this;
   }

   public function withUser(){  
      $userGroup = config('email_service.user_shortcode_group');
      $this->shortcodes += $this->getGroup( $userGroup );
      return $this;
   }

   /**
    * Get the group shortcodes from the config file.
    * 
    * @param  String  $group
    * @return Array 
    */
   public function getGroup( $group_slug ){
      return isset(config('email_service.shortcodes')[ $group_slug ]) 
               ? config('email_service.shortcodes')[ $group_slug ] 
               : [];
   }


   /**
    * Show the shortcodes list 
    *  
    * @return Array 
    */
   public function shortcodes(){
      return $this->shortcodes;
   }


   /**
    * Show the objects needed 
    *  
    * @return Array 
    */
   public function objectsNeeded(){ 

      $shortcodes_objects = array_filter(  $this->shortcodes, function( $item ){
         return $item['type'] != 'view';
      }); 

      $shortcodes_objects = array_column( $shortcodes_objects, 'object');
      $shortcodes_objects = array_unique( $shortcodes_objects );
      $shortcodes_objects = array_values( $shortcodes_objects );

      $views_objects = array_filter(  $this->shortcodes, function( $item ){
         return $item['type'] == 'view';
      });

      
      $words = [];
      foreach ($views_objects as $view) {
         $path = config('email_service.view_path').'/';

         $file = file_get_contents(resource_path( $path.str_replace('.', '\\', $view['object']).'.blade.php'));
         
         $regex = '~(\$\w+)~';
         if (preg_match_all($regex, $file, $matches, PREG_PATTERN_ORDER)) {
            foreach ($matches[1] as $word) {
               $file_words[] = $word;
            }
         }

         $file_words = array_unique( $file_words ); 
         array_push($words, [ $view['object'] => $file_words ]);
      }

      return [
         'Shortcode variables' => $shortcodes_objects,
         'Views variables' => $words         
      ];
   }

}