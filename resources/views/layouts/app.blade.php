<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <title>@yield('title', 'Restaurant POS')</title>
</head>
<body class="bg-gray-100 p-6 md:p-8">
    
    @include('partials.topbar')

    <main>
        @yield('content')
    </main>

</body>
</html>