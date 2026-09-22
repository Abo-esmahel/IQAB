<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'IQAB') }}</title>
    <link rel="icon" href="{{ asset('images/logo.jpg') }}" type="image/jpeg">
    <link rel="shortcut icon" href="{{ asset('images/logo.jpg') }}" type="image/jpeg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-dark-950 text-dark-100 font-sans antialiased overflow-x-clip">
    <div aria-hidden="true" class="pointer-events-none fixed inset-x-0 top-0 h-[42rem] bg-[radial-gradient(60rem_26rem_at_50%_-8rem,rgba(216,156,43,0.08),transparent)]"></div>
    <div class="relative min-h-full" x-data="{ sidebarOpen: false }">
        @auth
            @include('layouts.partials.sidebar')
        @endauth

        <div class="{{ auth()->check() ? 'lg:ps-64' : '' }} min-h-screen flex flex-col">
            @include('layouts.partials.navbar')

            <main class="flex-1">
                @if(session('success'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="rounded-lg bg-emerald-500/10 border border-emerald-500/20 p-4 flex items-center gap-3">
                            <svg class="h-5 w-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm text-emerald-300">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="rounded-lg bg-red-500/10 border border-red-500/20 p-4 flex items-center gap-3">
                            <svg class="h-5 w-5 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                            <p class="text-sm text-red-300">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif
                @if($errors->any())
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="rounded-lg bg-red-500/10 border border-red-500/20 p-4">
                            <ul class="list-disc list-inside text-sm text-red-300 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="border-t border-dark-800 py-6 text-center text-sm text-dark-500">
                &copy; {{ date('Y') }} {{ config('app.name', 'IQAB') }}. All rights reserved.
            </footer>
        </div>

        @auth
            @include('layouts.partials.mobile-sidebar')
        @endauth
    </div>

    <script>
        (function () {
            var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (reduce) return;

            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (e.isIntersecting) {
                        e.target.classList.add('reveal-visible');
                        io.unobserve(e.target);
                    }
                });
            }, { threshold: 0.12 });

            document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });

            var counters = document.querySelectorAll('[data-count]');
            if (counters.length) {
                var cio = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (!entry.isIntersecting) return;
                        var el = entry.target;
                        var end = parseInt(el.dataset.count.replace(/[^0-9]/g, ''), 10) || 0;
                        var dur = 1200, start = null;
                        function step(t) {
                            if (!start) start = t;
                            var p = Math.min((t - start) / dur, 1);
                            el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3)));
                            if (p < 1) requestAnimationFrame(step);
                        }
                        requestAnimationFrame(step);
                        cio.unobserve(el);
                    });
                }, { threshold: 0.5 });
                counters.forEach(function (c) { cio.observe(c); });
            }
        })();
    </script>
</body>
</html>