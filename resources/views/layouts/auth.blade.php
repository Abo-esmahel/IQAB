<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'IQAB' }}</title>
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
    </style>
</head>
<body class="h-full bg-dark-950 text-dark-100">
    <div aria-hidden="true" class="pointer-events-none fixed inset-x-0 top-0 h-[42rem] bg-[radial-gradient(60rem_26rem_at_50%_-8rem,rgba(216,156,43,0.08),transparent)]"></div>
    <div class="relative min-h-full flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
<div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                <img src="{{ asset('images/logo.jpg') }}" alt="IQAB" class="h-10 w-10 rounded-lg object-cover">
                <span class="text-2xl font-bold bg-gradient-to-r from-primary-300 via-primary-400 to-primary-600 bg-clip-text text-transparent">IQAB</span>
            </a>
        </div>

            @if(session('success'))
                <div class="rounded-lg bg-emerald-500/10 border border-emerald-500/20 p-4 mb-6">
                    <p class="text-sm text-emerald-300 text-center">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-lg bg-red-500/10 border border-red-500/20 p-4 mb-6">
                    <p class="text-sm text-red-300 text-center">{{ session('error') }}</p>
                </div>
            @endif
            @if($errors->any())
                <div class="rounded-lg bg-red-500/10 border border-red-500/20 p-4 mb-6">
                    <ul class="list-disc list-inside text-sm text-red-300 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</body>
</html>