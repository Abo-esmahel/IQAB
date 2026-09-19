<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="IQAB - Virtual phone numbers, SMS inbox, Telegram tools and digital services on one premium platform.">
    <title>IQAB - Virtual Phone Numbers Platform</title>
    <link rel="icon" href="{{ asset('images/logo.jpg') }}" type="image/jpeg">
    <link rel="shortcut icon" href="{{ asset('images/logo.jpg') }}" type="image/jpeg">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
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

        .reveal { opacity: 0; transform: translateY(26px); transition: opacity .7s ease-out, transform .7s ease-out; will-change: opacity, transform; }
        .reveal.reveal-visible { opacity: 1; transform: none; }

        .float-slow { animation: float-slow 9s ease-in-out infinite; }
        .float-slower { animation: float-slow 13s ease-in-out infinite reverse; }
        @keyframes float-slow { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-22px); } }

        .eagle-swoop {
            animation: iqab-swoop 1.1s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        @keyframes iqab-swoop {
            0% { transform: translate(150px, -210px) scale(0.45) rotate(-16deg); opacity: 0; filter: blur(8px); }
            60% { transform: translate(-8px, 12px) scale(1.05) rotate(2deg); opacity: 1; filter: blur(0); }
            100% { transform: translate(0, 0) scale(1) rotate(0deg); opacity: 1; filter: blur(0); }
        }
        .eagle-glow { animation: iqab-glow 4s ease-in-out infinite; }
        @keyframes iqab-glow {
            0%, 100% { opacity: 0.45; transform: scale(1); }
            50% { opacity: 0.95; transform: scale(1.08); }
        }
        @keyframes iqab-progress {
            from { width: 0%; }
            to { width: 100%; }
        }

        .btn-gold { position: relative; overflow: hidden; }
        .btn-gold::after {
            content: '';
            position: absolute;
            inset-block-start: 0;
            inset-block-end: 0;
            inset-inline-start: -150%;
            width: 55%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.28), transparent);
            transform: skewX(-20deg);
            pointer-events: none;
        }
        .btn-gold:hover::after { animation: btn-shine .9s ease; }
        @keyframes btn-shine { to { inset-inline-start: 150%; } }

        .card-lift { transition: transform .3s ease, border-color .3s ease, box-shadow .3s ease, background-color .3s ease; }
        .card-lift:hover {
            transform: translateY(-6px);
            border-color: rgba(216, 156, 43, .4);
            box-shadow: 0 18px 40px -18px rgba(216, 156, 43, .35);
        }
        .card-lift:hover .feature-icon { background-color: rgba(216, 156, 43, .18); }

        .feature-icon { transition: background-color .3s ease; }

        .step-line { background: linear-gradient(180deg, transparent, rgba(216,156,43,.45), transparent); }

        .glow-ring { box-shadow: 0 0 0 1px rgba(216,156,43,.25), 0 20px 50px -24px rgba(216,156,43,.45); }
    </style>
</head>
<body class="min-h-full bg-dark-950 text-dark-100 font-sans antialiased">

    {{-- Opening splash: the eagle, its own screen for 10s --}}
    <div id="iqab-splash" class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-dark-950 transition-opacity duration-700">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 start-1/2 -translate-x-1/2 h-[36rem] w-[80rem] rounded-full bg-[radial-gradient(closest-side,rgba(216,156,43,0.11),transparent)]"></div>
            <div class="absolute bottom-0 start-1/2 -translate-x-1/2 h-72 w-[40rem] rounded-full bg-[radial-gradient(closest-side,rgba(216,156,43,0.08),transparent)]"></div>
        </div>

        <button id="iqab-splash-skip" type="button"
                class="absolute top-5 end-5 z-10 text-xs font-medium text-dark-500 hover:text-primary-400 transition-colors">
            Skip intro
        </button>

        <div class="relative mb-8 h-40 w-40 sm:h-56 sm:w-56 float-slow">
            <div class="eagle-glow absolute inset-4 rounded-full" style="background: radial-gradient(circle, rgba(216,156,43,0.5) 0%, rgba(216,156,43,0.12) 45%, transparent 70%);"></div>
            <img src="{{ asset('images/eagle.svg') }}" alt="IQAB Eagle" class="eagle-swoop relative w-full h-full object-contain select-none drop-shadow-[0_22px_42px_rgba(216,156,43,0.4)]">
        </div>

        <p dir="rtl" class="text-4xl sm:text-5xl font-extrabold text-gold-gradient">عِقاب</p>
        <p dir="rtl" id="iqab-splash-tag" class="mt-3 text-xl sm:text-2xl font-bold text-dark-200 transition-opacity duration-500">رؤيةٌ لا تُخطئُ</p>

        <div class="mt-10 h-1 w-56 sm:w-64 overflow-hidden rounded-full bg-dark-800">
            <div id="iqab-splash-progress" class="h-full rounded-full bg-gradient-to-r from-primary-600 via-primary-500 to-primary-300" style="animation: iqab-progress 10s linear forwards;"></div>
        </div>
    </div>

    {{-- Ambient background --}}
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -top-40 start-1/2 -translate-x-1/2 h-[36rem] w-[80rem] rounded-full bg-[radial-gradient(closest-side,rgba(216,156,43,0.09),transparent)]"></div>
        <div class="absolute -top-24 -start-32 h-96 w-96 rounded-full bg-primary-500/10 blur-3xl float-slow"></div>
        <div class="absolute top-1/3 -end-32 h-96 w-96 rounded-full bg-primary-700/10 blur-3xl float-slower"></div>
    </div>

    {{-- Navbar --}}
    <nav x-data="{ scrolled: false, open: false }"
         @scroll.window="scrolled = window.scrollY > 10"
         :class="scrolled ? 'bg-dark-900/90 shadow-lg shadow-black/30 border-b border-dark-800/80' : 'bg-transparent border-b border-transparent'"
         class="fixed inset-x-0 top-0 z-50 backdrop-blur-xl transition-all duration-300">
        <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0">
                <img src="{{ asset('images/logo.jpg') }}" alt="IQAB" class="h-9 w-9 rounded-lg object-cover ring-1 ring-primary-500/30">
                <span class="text-xl font-bold text-gold-gradient">IQAB</span>
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm text-dark-400">
                <a href="#features" class="hover:text-primary-400 hover:text-primary-300 transition-colors">Features</a>
                <a href="#how-it-works" class="hover:text-primary-400 transition-colors">How It Works</a>
                <a href="{{ route('marketplace') }}" class="hover:text-primary-400 transition-colors">Marketplace</a>
                <a href="{{ route('contact') }}" class="hover:text-primary-400 transition-colors">Support</a>
            </div>

            <div class="hidden md:flex items-center gap-3 shrink-0">
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-medium text-dark-300 hover:text-white transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="btn-gold inline-flex items-center gap-2 rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 hover:shadow-[0_0_24px_rgba(216,156,43,0.3)] transition-all">Get Started</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn-gold inline-flex items-center gap-2 rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 hover:shadow-[0_0_24px_rgba(216,156,43,0.3)] transition-all">Dashboard</a>
                @endguest
            </div>

            <button @click="open = !open" class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-lg border border-dark-700 text-dark-300 hover:text-white hover:bg-dark-800 transition-colors" :aria-expanded="open" aria-label="Menu">
                <svg x-show="!open" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="open" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="md:hidden border-t border-dark-800 bg-dark-900/95 backdrop-blur-xl px-4 py-4 space-y-1">
            <a href="#features" @click="open = false" class="block rounded-lg px-3 py-2.5 text-sm text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">Features</a>
            <a href="#how-it-works" @click="open = false" class="block rounded-lg px-3 py-2.5 text-sm text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">How It Works</a>
            <a href="{{ route('marketplace') }}" @click="open = false" class="block rounded-lg px-3 py-2.5 text-sm text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">Marketplace</a>
            <a href="{{ route('contact') }}" @click="open = false" class="block rounded-lg px-3 py-2.5 text-sm text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">Support</a>
            <div class="pt-3 mt-2 border-t border-dark-800 flex flex-col gap-2">
                @guest
                    <a href="{{ route('login') }}" @click="open = false" class="rounded-lg border border-dark-700 px-4 py-2.5 text-center text-sm font-medium text-dark-200 hover:bg-dark-800 transition-colors">Login</a>
                    <a href="{{ route('register') }}" @click="open = false" class="btn-gold rounded-lg bg-primary-600 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-primary-700 transition-colors">Get Started</a>
                @else
                    <a href="{{ route('dashboard') }}" @click="open = false" class="btn-gold rounded-lg bg-primary-600 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-primary-700 transition-colors">Dashboard</a>
                @endguest
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="relative overflow-hidden pt-32 pb-20 sm:pt-44 sm:pb-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center max-w-3xl mx-auto">

                <div class="relative mx-auto mb-10 h-48 w-48 sm:h-60 sm:w-60 float-slow" style="animation-delay:0.1s">
                    <div class="eagle-glow absolute inset-4 rounded-full" style="background: radial-gradient(circle, rgba(216,156,43,0.5) 0%, rgba(216,156,43,0.12) 45%, transparent 70%);"></div>
                    <img src="{{ asset('images/eagle.svg') }}" alt="IQAB Eagle" class="eagle-swoop relative w-full h-full object-contain select-none drop-shadow-[0_22px_42px_rgba(216,156,43,0.35)]">
                </div>

                <div class="reveal inline-flex items-center gap-2 rounded-full bg-primary-500/10 border border-primary-500/25 px-4 py-1.5 text-sm text-primary-400">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                    </span>
                    Platform Live Now
                </div>

                <h1 class="reveal mt-6 text-4xl sm:text-6xl lg:text-7xl font-bold text-white leading-[1.1]" style="transition-delay:80ms">
                    Virtual Phone Numbers<br><span class="text-gold-gradient">Made Simple</span>
                </h1>

                <p class="reveal mt-6 text-lg text-dark-400 max-w-2xl mx-auto" style="transition-delay:160ms">
                    Purchase virtual phone numbers, receive SMS messages instantly, and manage everything from one powerful dashboard.
                </p>

                <p dir="rtl" id="iqab-tagline" class="reveal mt-5 text-2xl sm:text-3xl font-bold text-gold-gradient transition-opacity duration-500" style="min-height: 2.5rem;">
                    رؤيةٌ لا تُخطئُ
                </p>

                <div class="reveal mt-10 flex flex-col sm:flex-row gap-4 justify-center" style="transition-delay:240ms">
                    <a href="{{ route('register') }}" class="btn-gold inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-lg bg-primary-600 px-7 py-3.5 text-sm font-semibold text-white hover:bg-primary-700 hover:shadow-[0_0_28px_rgba(216,156,43,0.35)] translate-y-0 hover:-translate-y-0.5 transition-all">
                        Start Now
                        <svg class="h-4 w-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="{{ route('marketplace') }}" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-lg bg-dark-800/70 border border-dark-700 px-7 py-3.5 text-sm font-semibold text-dark-200 hover:bg-dark-700 hover:border-primary-500/30 hover:text-white transition-all">
                        Explore Marketplace
                    </a>
                </div>

                <div class="reveal mt-8 flex items-center justify-center gap-2 text-xs text-dark-500" style="transition-delay:320ms">
                    <svg class="h-4 w-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    No credit card required · Instant activation · Telegram support
                </div>
            </div>

            {{-- Stats band --}}
            <div class="reveal mt-16 grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-3xl mx-auto" style="transition-delay:400ms">
                @php
                $stats = [
                    ['value' => 120, 'suffix' => '+', 'label' => 'Numbers Live'],
                    ['value' => 24,  'suffix' => '/7', 'label' => 'Support'],
                    ['value' => 100, 'suffix' => '%', 'label' => 'Secure'],
                ];
                @endphp
                @foreach($stats as $stat)
                    <div class="rounded-xl bg-dark-900/60 border border-dark-800 backdrop-blur-sm px-6 py-5 text-center card-lift">
                        <p class="text-3xl font-bold text-gold-gradient"><span data-count="{{ $stat['value'] }}">0</span>{{ $stat['suffix'] }}</p>
                        <p class="mt-1 text-sm text-dark-400">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                <a href="#features" aria-label="Scroll to features" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-dark-700 text-dark-500 hover:text-primary-400 hover:border-primary-500/40 transition-colors animate-bounce">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="py-20 sm:py-24 border-t border-dark-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <p class="reveal text-sm font-medium text-primary-400 uppercase tracking-widest">Features</p>
                <h2 class="reveal mt-2 text-3xl sm:text-4xl font-bold text-white" style="transition-delay:60ms">Everything You Need</h2>
                <p class="reveal mt-3 text-dark-400 max-w-2xl mx-auto" style="transition-delay:120ms">A complete platform for virtual phone number management</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                $features = [
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>', 'title' => 'Virtual Numbers', 'desc' => 'Browse and purchase virtual numbers from multiple countries worldwide.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>', 'title' => 'SMS Inbox', 'desc' => 'Receive and view SMS messages in real-time with a clean, organized inbox.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>', 'title' => 'Telegram Tools', 'desc' => 'Access Telegram lookup, report, and information services.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>', 'title' => 'Secure & Private', 'desc' => 'Enterprise-grade security with encrypted data and a private platform.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>', 'title' => 'Instant Delivery', 'desc' => 'Numbers activated instantly. Messages delivered in real-time via webhooks.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>', 'title' => 'Wallet & Billing', 'desc' => 'Track purchases, order history and invoices from your personal dashboard.'],
                ];
                @endphp
                @foreach($features as $index => $feature)
                <div class="reveal rounded-xl bg-dark-900/70 border border-dark-800 backdrop-blur-sm p-6 card-lift" style="transition-delay:{{ $index * 50 }}ms">
                    <div class="feature-icon h-12 w-12 rounded-xl bg-primary-500/10 flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $feature['icon'] !!}</svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white">{{ $feature['title'] }}</h3>
                    <p class="mt-2 text-sm text-dark-400">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section id="how-it-works" class="py-20 sm:py-24 border-t border-dark-800/80">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <p class="reveal text-sm font-medium text-primary-400 uppercase tracking-widest">Process</p>
                <h2 class="reveal mt-2 text-3xl sm:text-4xl font-bold text-white" style="transition-delay:60ms">How It Works</h2>
            </div>
            <div class="relative grid sm:grid-cols-3 gap-10">
                <div aria-hidden="true" class="hidden sm:block absolute top-7 inset-x-16 step-line h-px"></div>
                @php
                $steps = [
                    ['num' => '01', 'title' => 'Create Account', 'desc' => 'Sign up in seconds with your email address.'],
                    ['num' => '02', 'title' => 'Browse Numbers', 'desc' => 'Explore available virtual numbers and digital services.'],
                    ['num' => '03', 'title' => 'Complete via Telegram', 'desc' => 'Reach our support on Telegram to finish your order.'],
                ];
                @endphp
                @foreach($steps as $index => $step)
                <div class="reveal relative text-center px-4" style="transition-delay:{{ $index * 90 }}ms">
                    <div class="relative mx-auto h-14 w-14 rounded-full bg-gradient-to-b from-primary-500/30 to-primary-700/10 border border-primary-500/30 flex items-center justify-center glow-ring">
                        <span class="text-xl font-bold text-gold-gradient">{{ $step['num'] }}</span>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-white">{{ $step['title'] }}</h3>
                    <p class="mt-2 text-sm text-dark-400">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="relative py-20 sm:py-28 overflow-hidden">
        <div aria-hidden="true" class="absolute inset-0 bg-[radial-gradient(40rem_20rem_at_50%_120%,rgba(216,156,43,0.12),transparent)]"></div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="reveal text-3xl sm:text-4xl font-bold text-white">Ready to Get Started?</h2>
            <p class="reveal mt-4 text-dark-400" style="transition-delay:60ms">Join thousands of users who trust IQAB for their virtual number needs.</p>
            <div class="reveal mt-9 flex flex-col sm:flex-row gap-4 justify-center" style="transition-delay:120ms">
                <a href="{{ route('register') }}" class="btn-gold inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-lg bg-primary-600 px-8 py-3.5 text-sm font-semibold text-white hover:bg-primary-700 hover:shadow-[0_0_28px_rgba(216,156,43,0.35)] transition-all">
                    Create Free Account
                    <svg class="h-4 w-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="{{ route('contact') }}" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-lg bg-dark-800/70 border border-dark-700 px-8 py-3.5 text-sm font-semibold text-dark-200 hover:bg-dark-700 hover:border-primary-500/30 hover:text-white transition-all">
                    <svg class="h-4 w-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Contact on Telegram
                </a>
            </div>
        </div>
    </section>

    <footer class="relative border-t border-dark-800/80 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo.jpg') }}" alt="IQAB" class="h-7 w-7 rounded-lg object-cover">
                <span class="text-sm font-semibold text-gold-gradient">IQAB</span>
                <span class="text-sm text-dark-500">&copy; {{ date('Y') }} All rights reserved.</span>
            </div>
            <div class="flex flex-wrap justify-center gap-6 text-sm text-dark-500">
                <a href="{{ route('marketplace') }}" class="hover:text-dark-300 transition-colors">Marketplace</a>
                <a href="{{ route('contact') }}" class="hover:text-dark-300 transition-colors">Support</a>
                <a href="#" class="hover:text-dark-300 transition-colors">Privacy</a>
                <a href="#" class="hover:text-dark-300 transition-colors">Terms</a>
            </div>
        </div>
    </footer>

    <script>
        (function () {
            var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (reduce) return;

            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('reveal-visible');
                        io.unobserve(entry.target);
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
                        var end = parseInt(el.dataset.count, 10) || 0;
                        var dur = 1200;
                        var start = null;
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

            var phrases = [
                'رؤيةٌ لا تُخطئُ',
                'مَخالبُ لا تُفوّتُ',
                'سُرعةٌ لا تُجارى',
                'دِقّةٌ لا تُضاهى',
                'حُضورٌ لا يُنافَس'
            ];
            var tag = document.getElementById('iqab-tagline');
            if (tag) {
                var i = 0;
                setInterval(function () {
                    tag.style.opacity = '0';
                    setTimeout(function () {
                        i = (i + 1) % phrases.length;
                        tag.textContent = phrases[i];
                        tag.style.opacity = '1';
                    }, 450);
                }, 2600);
            }
        })();
    </script>

    <script>
        (function () {
            var splash = document.getElementById('iqab-splash');
            if (!splash) return;

            document.body.style.overflow = 'hidden';
            var done = false;
            function dismiss() {
                if (done) return;
                done = true;
                document.body.style.overflow = '';
                splash.classList.add('opacity-0', 'pointer-events-none');
                setTimeout(function () {
                    splash.style.display = 'none';
                }, 700);
            }

            var skip = document.getElementById('iqab-splash-skip');
            if (skip) {
                skip.addEventListener('click', dismiss);
            }

            var splashPhrases = [
                'رؤيةٌ لا تُخطئُ',
                'مَخالبُ لا تُفوّتُ',
                'سُرعةٌ لا تُجارى',
                'دِقّةٌ لا تُضاهى',
                'حُضورٌ لا يُنافَس'
            ];
            var splashTag = document.getElementById('iqab-splash-tag');
            if (splashTag) {
                var si = 0;
                setInterval(function () {
                    splashTag.style.opacity = '0';
                    setTimeout(function () {
                        si = (si + 1) % splashPhrases.length;
                        splashTag.textContent = splashPhrases[si];
                        splashTag.style.opacity = '1';
                    }, 450);
                }, 2500);
            }

            setTimeout(dismiss, 10000);
        })();
    </script>
</body>
</html>