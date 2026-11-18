<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#08090b">
    <title>@yield('title', 'Note App')</title>

    {{-- Bootstrap và JS/CSS của dự án được nạp qua Vite --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @yield('css')

    @yield('style')
</head>
<body>

    {{-- Header --}}
    <header class="gc-header d-flex align-items-center">
        <nav class="container-fluid px-3 px-md-4 px-xl-5 d-flex align-items-center justify-content-between"
             aria-label="Điều hướng chính">

            {{-- Logo --}}
            <a class="d-inline-flex align-items-center gap-2"
               href="{{ url('/') }}"
               aria-label="GhiChú - Trang chủ">

                <span class="gc-brand-icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24"
                         fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 4.5h9.5L20 8v11.5H7V4.5Z"
                              stroke="currentColor" stroke-width="1.7"
                              stroke-linejoin="round"/>
                        <path d="M16 4.8V8h3.2M4 8h2M4 12h2M4 16h2M10 12h6M10 15.5h6"
                              stroke="currentColor" stroke-width="1.7"
                              stroke-linecap="round"/>
                    </svg>
                </span>

                <span class="gc-brand">Note<span>App</span></span>
            </a>

            {{-- Navigation buttons --}}
            <div class="gc-nav d-flex align-items-center gap-2">
                
                @yield('navbar')
            
            </div>
        </nav>
    </header>

    {{-- Nội dung từng trang --}}
    {{-- <main>
        
    </main> --}}
    @yield('content')

    {{-- Footer
    <footer class="text-center mt-5">
        <p>Copyright 2026</p>
    </footer> --}}

    @stack('scripts')
</body>
</html>

