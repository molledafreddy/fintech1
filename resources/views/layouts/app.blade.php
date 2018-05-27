<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <title>{{ config('app.name', 'FINTECH' ) }}</title>

      <!-- Styles -->
      <link href="{{ asset('css/app.css') }}" rel="stylesheet">
      
  </head>
  <body>
    <div id="app">
      @include('layouts.nav') 
      <main>
        @yield('content')
      </main>
      @include('layouts.footer')
    </div>
    <!-- Scripts -->
        @yield('scripts')
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>