<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'IQAB') }}</title>
    <link rel="icon" href="{{ asset('images/logo.jpg') }}" type="image/jpeg">
    <link rel="shortcut icon" href="{{ asset('images/logo.jpg') }}" type="image/jpeg">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Instrument Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: { 50: '#fdf9ec', 100: '#f9f0cd', 200: '#f2df9c', 300: '#eac968', 400: '#e2b342', 500: '#d89c2b', 600: '#b8861f', 700: '#8f6518', 800: '#754f19', 900: '#634317', 950: '#38250b' },
                        dark: { 50: '#f8f9fa', 100: '#eef0f2', 200: '#dfe2e6', 300: '#c3c8cf', 400: '#9aa2ad', 500: '#727a86', 600: '#565d68', 700: '#3f454e', 800: '#262b33', 900: '#161a21', 950: '#0a0d13' }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }

        .text-gold-gradient {
            background: linear-gradient(120deg, #eac968 0%, #d89c2b 30%, #8f6518 50%, #d89c2b 70%, #eac968 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: gold-shimmer 7s linear infinite;
        }
        @keyframes gold-shimmer { to { background-position: 200% center; } }

        .fade-up { opacity: 0; transform: translateY(22px); animation: fade-up .7s cubic-bezier(.16,1,.3,1) forwards; }
        @keyframes fade-up { to { opacity: 1; transform: none; } }

        .reveal { opacity: 0; transform: translateY(26px); transition: opacity .7s ease-out, transform .7s ease-out; will-change: opacity, transform; }
        .reveal.reveal-visible { opacity: 1; transform: none; }

        .card-lift { transition: transform .3s ease, border-color .3s ease, box-shadow .3s ease, background-color .3s ease; }
        .card-lift:hover {
            transform: translateY(-6px);
            border-color: rgba(216, 156, 43, .4);
            box-shadow: 0 18px 40px -18px rgba(216, 156, 43, .35);
        }

        .card-topline { background: linear-gradient(90deg, transparent, rgba(216, 156, 43, .55), transparent); }

        .float-slow { animation: float-slow 9s ease-in-out infinite; }
        @keyframes float-slow { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-18px); } }

        .bar-grow { animation: bar-grow 1s cubic-bezier(.16,1,.3,1) both; transform-origin: bottom; }
        @keyframes bar-grow { from { transform: scaleY(0); } to { transform: scaleY(1); } }

        .glow-ring { box-shadow: 0 0 0 1px rgba(216,156,43,.25), 0 20px 50px -24px rgba(216,156,43,.45); }

        .flash-pulse { animation: flash-pulse 1.2s ease; }
        @keyframes flash-pulse {
            0% { text-shadow: 0 0 0 rgba(216, 156, 43, 0); }
            30% { text-shadow: 0 0 24px rgba(216, 156, 43, .95), 0 0 60px rgba(216, 156, 43, .4); transform: scale(1.12); }
            100% { text-shadow: 0 0 0 rgba(216, 156, 43, 0); transform: scale(1); }
        }

        .btn-gold { position: relative; overflow: hidden; }
        .btn-gold::after {
            content: '';
            position: absolute;
            inset-block-start: 0;
            inset-block-end: 0;
            inset-inline-start: -150%;
            width: 55%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.25), transparent);
            transform: skewX(-20deg);
            pointer-events: none;
        }
        .btn-gold:hover::after { animation: btn-shine .9s ease; }
        @keyframes btn-shine { to { inset-inline-start: 150%; } }
    </style>
</head>
<body class="h-full bg-dark-950 text-dark-100 font-sans antialiased">
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