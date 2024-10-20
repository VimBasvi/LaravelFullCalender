<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My Laravel App')</title>
</head>
<body>
    <header>
        <!-- Header content like navbars, etc. -->
    </header>
    
    <main>
        <!-- Here the content of individual pages will be displayed -->
        @yield('content')
    </main>
    
    <footer>
        <!-- Footer content -->
    </footer>

    <!-- Stack for including additional scripts on specific pages -->
    @stack('scripts') <!--Place where scripts will be injected-->
</body>
</html>
