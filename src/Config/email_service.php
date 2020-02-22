<?php

return [ 

   // Blade views folder
   'view_path' => 'views',
   
   // Email template path inside the view_path
   'email_template' => 'email_service.view',

   // User shortcode group
   'user_shortcode_group' => 'common',

   /* 
   *  Shortcodes
   *
   * 'shortcode_unique_name' => [
   *     'title' => 'Title shown inside the editor in the backend'
   *     'type' => 'shortcode type' 
   *        // variable
   *        // function
   *        // view
   *     'object' => ''
   *        // eloquent object for variable or function
   *        // view path in view
   *     'param' => ''
   *        // variable
   *        // function 
   *        // null for view
   *  ]
   */
   'shortcodes' => [

      'common' => [
         'user_first_name' => [
            'title' => 'First name',
            'type' => 'variable',
            'object' => 'user',
            'param' => 'first_name'
         ], 
         'user_last_name' => [
            'title' => 'Last name',
            'type' => 'variable',
            'object' => 'user',
            'param' => 'last_name'
         ], 
         'user_email_address' => [
            'title' => 'Email address',
            'type' => 'variable',
            'object' => 'user',
            'param' => 'email'
         ], 
         'user_phone_number' => [
            'title' => 'Phone number',
            'type' => 'variable',
            'object' => 'user',
            'param' => 'phone'
         ], 
         'user_address_1' => [
            'title' => 'Address 1',
            'type' => 'variable',
            'object' => 'user',
            'param' => 'address_1'
         ], 
         'user_address_2' => [
            'title' => 'Address 2',
            'type' => 'variable',
            'object' => 'user',
            'param' => 'address_2'
         ], 
         'user_city' => [
            'title' => 'City',
            'type' => 'variable',
            'object' => 'user',
            'param' => 'city'
         ], 
         'user_county' => [
            'title' => 'County',
            'type' => 'variable',
            'object' => 'user',
            'param' => 'county'
         ], 
         'user_postcode' => [
            'title' => 'Postcode',
            'type' => 'variable',
            'object' => 'user',
            'param' => 'postcode'
         ], 
         'user_country' => [
            'title' => 'Country',
            'type' => 'variable',
            'object' => 'country',
            'param' => 'title'
         ],   
      ],

   ]

];