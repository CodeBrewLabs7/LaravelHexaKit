{{-- <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @yield('page_title')
        <title>Royo HMVC | Delivery Option</title>
        <link rel="stylesheet" href="{{ asset('css/auth-module.css') }}">
        @yield('page_css')

       {{-- Laravel Mix - CSS File --}}
       {{-- <link rel="stylesheet" href="{{ mix('css/deliveryoption.css') }}"> --}}

    {{-- </head>
    <body>
        @yield('content') --}}

        {{-- Laravel Mix - JS File --}}
        {{-- <script src="{{ mix('js/deliveryoption.js') }}"></script> --}}
    {{-- </body>
    @yield('page_js')
</html> --}}

<!DOCTYPE html>
<html lang="en">

    @include('adminpanel::layouts.includes.header')

    <body class="nav-md">

        <div class="container body">

          <div class="main_container">

            @include('adminpanel::layouts.includes.left_menu_bar')

            @extends('deliveryoptions::layouts.alerts')
            

            @include('adminpanel::layouts.includes.top_nevigation')

            @yield('content')

            @include('adminpanel::layouts.includes.footer')

          </div>

        </div>

        @include('adminpanel::layouts.includes.footer_files')

        @yield('page-script')

        @stack('js')

    </body>
</html>
