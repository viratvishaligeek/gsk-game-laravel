<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('frontend.include.meta')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('frontend') }}/css/style.css">
    <script>
        document.documentElement.classList.add('js-reveal');
        setTimeout(function() {
            if (!document.querySelector('.reveal-run')) document.documentElement.classList.remove('js-reveal');
        }, 9000);
    </script>
    @yield('style')
    @stack('schema')
</head>

<body>
    @include('frontend.include.header')
    <main>
        @yield('content')
    </main>
    @include('frontend.include.footer')
    @if (request()->routeIs('index') && isset($homepageSections))
        @include('frontend.include.homepage-sections', ['sections' => $homepageSections->get('after_footer', collect())])
    @endif
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="{{ URL::asset('frontend') }}/js/app.js"></script>
    @yield('script')
</body>

</html>
