# Email service plugin for laravel 5|6|7|8

This package simplify compiling shortcodes and sending emails.

## Requirements
- [PHP >= 7.0](http://php.net/)
- [Laravel >=5.x(https://github.com/laravel/framework)

## Installation
**Install this package with composer:**
```bash
$ composer require twindots/email-service
```
Service Provider & Facade will be discovered automatically by laravel. 
**Publish the config file and views folder:** *(required)*
```bash
$ php artisan vendor:publish --provider="TwinDots\EmailService\EmailServiceProvider"
```
This will publish the following:
 - config/email_service.php
 - views/email_service/views.blade.php
 - views/email_service/layout.blade.php
 - views/email_service/partials/

## Usage
This package consist of 2 classes: EmailService and EmailShortCodes.
 ### 1- EmailShortCodes:
- Import the library:
```php
   use EmailShortCodes; 
```
- Load it in your function:
```php
   public function compileCodes( EmailShortCodes $shortcodes )
   { 
      // Or create a new instance
      // $shortcodes = new EmailShortCodes();

      $compiled = $shortcodes->objects([
                  'user' => $user,
               ])
               ->withUser()
               ->body( request('body') )
               ->compile(); 
   }
```
 ### 2- EmailService:
- Import the library:
```php
   use EmailService; 
```
- Load it in your function:
```php
   public function sendEmail( EmailService $emailService )
   { 
      // Or create a new instance
      // $emailService = new EmailService();

      $result = $emailService->email(['email1@example.com', 'email2@example.com'])
                  ->subject( $subject )
                  ->body( $compiled ) // Send the compiled body or any html
                  ->attach([
                     'file-1.png' => 'path/to/file-1.png',
                     'file-2.pdf' => 'path/to/file-2.png'
                  ])
                  ->send();
   }
```
- this library will use the file **email_service/view.blade** for the email template, you can change it from the config file.
- The `send()` function will return a result array having:
  - status (boolean): **True** for success and **false** for failed delivery.
  - Message (text) : Show the message of a failed delivery.

## Shortcodes
Shortcodes are defined inside the config file **config/email_service.php** under the **shortcodes** array.
You can define shortcodes inside groups in order to load each group for different email templates.
 ```php
    'group_name' => [
      'short_code_1' => [...],
      'short_code_2' => [...],
    ]   
 ```
Shortcodes can be inserted in your favorite text editor with this command:
```
 {shortcode_unique_name}
```
**P.S: This library will compile anything inside {}**

You can add 3 types of shortcodes:
 - **Variable**:
 ```php
   'user_first_name' => [        // shortcode unique name
      'title' => 'First name',   // shortcode friendly name
      'type' => 'variable',      // type is variable 
      'object' => 'user',        // object can be any class, ex: $user
      'param' => 'first_name'    // parameter, ex first_name: $user->first_name
   ], 
 ```
 - **Function**:
 ```php
   'user_full_name' => [        // shortcode unique name
      'title' => 'Full name',   // shortcode friendly name
      'type' => 'function',     // type is function 
      'object' => 'user',       // object can be any class, ex: $user
      'param' => 'getFullName'  // parameter, ex: getFullName: $user->getFullName()
   ], 
 ```
 - **Blade view**:
 ```php
   'user_image' => [                      // shortcode unique name
      'title' => 'User image',            // shortcode friendly name
      'type' => 'view',                   // type is view 
      'object' => 'users.profile-image',  // object is the view path
   ], 
 ```
 For the blade view shortcode, you don't need to pass any objects since it will inherit the objects passed from the `$shortcodes->objects()` function.

### How to check which objects are required:
 If you are working with a large list of shortcodes inside a template with multiple variables and views, you need to know what are the required objects, simply call for the function `$shortcodes->objectsNeeded()`, it will return an array that will tell you what objects are needed.

## Reference
### EmailShortCodes
 Methods          | Parameters         | Definition
:-----------------|:-------------------|:-------------------
compile()         | -                  | Will compile the **body** using the **shortcodes** list and **objects**
body()            | *(text)* $body       | Set the body
objects()         | *(array)* $objects   | Set the objects
group()           | *(array)* $groups    | Set the group
withUser()        | -                  | Add the user shortcode group to the shortcodes list
getGroup()        | -                  | Return the group
shortcodes()      | -                  | Return the shortcodes list
objectsNeeded()   | -                  | Return the objects needed to compile

### EmailService
 Methods          | Parameters         | Definition
:-----------------|:-------------------|:-------------------
email()           | *(string)* $email    | Set the email
body()            | *(text)* $body       | Set the body
subject()         | *(string)* $subject       | Set the subject
attach()          | *(array)* $attachments   | Set the attachments
send()            | -    | Send the email

## License
The MIT License (MIT). Please see [License File](https://github.com/.../blob/master/LICENSE.md) for more information.
