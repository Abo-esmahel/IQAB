<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQAB - Virtual Phone Numbers Platform</title>
    <link rel="icon" href="{{ asset('images/logo.jpg') }}" type="image/jpeg">
    <link rel="shortcut icon" href="{{ asset('images/logo.jpg') }}" type="image/jpeg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a' },
                        dark: { 50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0', 300: '#cbd5e1', 400: '#94a3b8', 500: '#64748b', 600: '#475569', 700: '#334155', 800: '#1e293b', 900: '#0f172a', 950: '#020617' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-dark-950 text-dark-100">

<nav class="sticky top-0 z-50 bg-dark-900/80 backdrop-blur-xl border-b border-dark-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.jpg') }}" alt="IQAB" class="h-8 w-8 rounded-lg object-cover">
                <span class="text-xl font-bold text-white">IQAB</span>
            </div>
            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-medium text-dark-300 hover:text-white transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">Get Started</a>
                @else
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">Dashboard</a>
                @endguest
            </div>
        </div>
    </div>
</nav>

<section class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-primary-600/10 to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 relative">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 rounded-full bg-primary-500/10 border border-primary-500/20 px-4 py-1.5 text-sm text-primary-400 mb-6">
                <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span></span>
                Platform Live Now
            </div>
            <h1 class="text-4xl sm:text-6xl font-bold text-white leading-tight">
                Virtual Phone Numbers<br><span class="text-primary-400">Made Simple</span>
            </h1>
            <p class="mt-6 text-lg text-dark-400 max-w-2xl mx-auto">
                Purchase virtual phone numbers, receive SMS messages instantly, and manage everything from one powerful dashboard.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-6 py-3 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">
                    Start Now
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="#features" class="inline-flex items-center justify-center gap-2 rounded-lg bg-dark-800 border border-dark-700 px-6 py-3 text-sm font-semibold text-dark-200 hover:bg-dark-700 transition-colors">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>

<section id="features" class="py-20 border-t border-dark-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-white">Everything You Need</h2>
            <p class="mt-3 text-dark-400 max-w-2xl mx-auto">A complete platform for virtual phone number management</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $features = [
                ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>', 'title' => 'Virtual Numbers', 'desc' => 'Browse and purchase virtual numbers from multiple countries worldwide.'],
                ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>', 'title' => 'SMS Inbox', 'desc' => 'Receive and view SMS messages in real-time with a clean, organized inbox.'],
                ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>', 'title' => 'Telegram Tools', 'desc' => 'Access Telegram lookup, report, and information services.'],
                ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>', 'title' => 'Secure & Private', 'desc' => 'Enterprise-grade security with encrypted data and a private platform.'],
                ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>', 'title' => 'Instant Delivery', 'desc' => 'Numbers activated instantly. Messages delivered in real-time via webhooks.'],
            ];
            @endphp
            @foreach($features as $feature)
            <div class="rounded-xl bg-dark-900 border border-dark-800 p-6 hover:border-dark-700 transition-colors">
                <div class="h-12 w-12 rounded-xl bg-primary-500/10 flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $feature['icon'] !!}</svg>
                </div>
                <h3 class="text-lg font-semibold text-white">{{ $feature['title'] }}</h3>
                <p class="mt-2 text-sm text-dark-400">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-20 border-t border-dark-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-white">How It Works</h2>
        </div>
        <div class="grid sm:grid-cols-3 gap-8">
            @php
            $steps = [
                ['num' => '01', 'title' => 'Create Account', 'desc' => 'Sign up in seconds with your email address.'],
                ['num' => '02', 'title' => 'Browse Numbers', 'desc' => 'Explore available virtual numbers and services.'],
                ['num' => '03', 'title' => 'Contact via Telegram', 'desc' => 'Reach our support on Telegram to complete your order.'],
            ];
            @endphp
            @foreach($steps as $step)
            <div class="text-center">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-primary-600/20 text-primary-400 text-xl font-bold">{{ $step['num'] }}</div>
                <h3 class="mt-4 text-lg font-semibold text-white">{{ $step['title'] }}</h3>
                <p class="mt-2 text-sm text-dark-400">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-20 border-t border-dark-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white">Ready to Get Started?</h2>
        <p class="mt-4 text-dark-400">Join thousands of users who trust IQAB for their virtual number needs.</p>
        <div class="mt-8">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-8 py-3 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">
                Create Free Account
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</section>

<footer class="border-t border-dark-800 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <div class="h-6 w-6 rounded bg-primary-600 flex items-center justify-center"><span class="text-white text-xs font-bold">Q</span></div>
            <span class="text-sm text-dark-500">&copy; {{ date('Y') }} IQAB</span>
        </div>
        <div class="flex gap-6 text-sm text-dark-500">
            <a href="#" class="hover:text-dark-300">Privacy</a>
            <a href="#" class="hover:text-dark-300">Terms</a>
            <a href="#" class="hover:text-dark-300">Support</a>
        </div>
    </div>
</footer>

</body>
</html>