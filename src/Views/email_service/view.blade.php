@extends('email_service.layout')

@section('content')

   Text before content
   <br> <br>

   {!! $content !!}
   
   <br> <br>
   Text after content

@endsection