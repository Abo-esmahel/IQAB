<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'IQAB' }}</title>
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
<body class="h-full bg-dark-950 text-dark-100">
    <div class="min-h-full flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
<div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                <img src="{{ asset('images/logo.jpg') }}" alt="IQAB" class="h-10 w-10 rounded-lg object-cover">
                <span class="text-2xl font-bold text-white">IQAB</span>
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