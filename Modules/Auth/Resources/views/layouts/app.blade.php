<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($pageTitle) ? $pageTitle . ' | Royo HMVC' : 'Royo HMVC' }}</title>
    <link rel="stylesheet" href="{{ asset('css/auth-module.css') }}">
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
