@extends('layouts.app', ['title' => 'Contact Us - IQAB'])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-white mb-3">Contact Us</h1>
        <p class="text-dark-400">Get in touch with us through any of these channels.</p>
    </div>

    @if($contactMethods->count())
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($contactMethods as $method)
        <a href="{{ $method->url ?? '#' }}" target="_blank" rel="noopener"
           class="group rounded-xl bg-dark-900 border border-dark-800 p-6 hover:border-dark-700 transition-all text-center">
            <div class="w-14 h-14 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl" style="background-color: {{ $method->color ?? '#334155' }}20;">
                <span>{{ $method->display_icon }}</span>
            </div>
            <h3 class="font-semibold text-white group-hover:text-primary-400 transition-colors mb-1">{{ $method->name }}</h3>
            <p class="text-sm text-dark-400 font-mono">{{ $method->value }}</p>
            @if($method->description)
                <p class="text-xs text-dark-500 mt-2">{{ $method->description }}</p>
            @endif
            <span class="inline-flex items-center gap-1 text-xs text-primary-400 mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                Contact Now
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </span>
        </a>
        @endforeach
    </div>
    @else
    <div class="rounded-xl bg-dark-900 border border-dark-800 p-12 text-center">
        <svg class="h-16 w-16 text-dark-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <h2 class="text-xl font-semibold text-white mb-2">No contact methods available</h2>
        <p class="text-dark-400">Please check back later or email us directly.</p>
    </div>
    @endif
</div>
@endsection
