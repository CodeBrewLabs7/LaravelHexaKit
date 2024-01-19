<!DOCTYPE html>
<html lang="en">

    @include('adminpanel::layouts.includes.header')

    <body class="nav-md">

        <div class="container body">

          <div class="main_container">

            @include('adminpanel::layouts.includes.left_menu_bar')

            @include('adminpanel::layouts.includes.top_nevigation')

            @yield('content')

            @include('adminpanel::layouts.includes.footer')

          </div>

        </div>

        @include('adminpanel::layouts.includes.footer_files')

    </body>
</html>
