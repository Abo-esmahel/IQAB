@extends('layouts.app', ['title' => 'Contact Us - IQAB'])

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-10">
        <p class="text-sm font-medium text-primary-400 uppercase tracking-widest">Support 24/7</p>
        <h1 class="mt-2 text-3xl sm:text-4xl font-bold text-white">Contact Us</h1>
        <p class="mt-3 text-dark-400 max-w-xl mx-auto">Reach our team on your favorite platform — we usually reply within minutes.</p>
    </div>

    @if($contactMethods->count())
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($contactMethods as $method)
        @php $color = $method->color ?: '#d89c2b'; @endphp
        <a href="{{ $method->url ?? '#' }}" target="_blank" rel="noopener"
           class="group relative overflow-hidden rounded-2xl bg-dark-900 border border-dark-800 p-6 text-center transition-all hover:-translate-y-1 hover:border-primary-500/40 hover:shadow-[0_18px_40px_-18px_rgba(216,156,43,0.35)] flex flex-col">
            <div aria-hidden="true" class="absolute -top-16 start-1/2 -translate-x-1/2 h-40 w-64 rounded-full opacity-0 group-hover:opacity-100 transition-opacity" style="background: radial-gradient(closest-side, {{ $color }}26, transparent);"></div>

            <div class="relative mx-auto mb-4 flex h-20 w-20 items-center justify-center transition-transform group-hover:scale-105">
                <div aria-hidden="true" class="absolute inset-0 rounded-full blur-2xl" style="background-color: {{ $color }}40;"></div>
                <div class="relative drop-shadow-[0_8px_20px_rgba(0,0,0,0.65)]" style="color: {{ $color }};">
                    <x-brand-icon :type="$method->type" :image="$method->image" class="h-16 w-16" />
                </div>
            </div>

            <h3 class="relative font-bold text-white text-lg group-hover:text-primary-300 transition-colors">{{ $method->name }}</h3>
            <button type="button" data-copy="{{ $method->value }}"
                    class="relative mt-1 text-sm text-dark-400 font-mono hover:text-white transition-colors cursor-pointer"
                    title="Click to copy">{{ $method->value }}</button>
            @if($method->description)
                <p class="relative text-xs text-dark-500 mt-2 leading-relaxed">{{ $method->description }}</p>
            @endif

            <span class="flex-1 min-h-5"></span>

            <span class="relative mt-0 inline-flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-white transition-all hover:brightness-110 hover:shadow-lg"
                  style="background-color: {{ $color }};">
                Chat Now
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
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

<script>
    document.querySelectorAll('[data-copy]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var done = function () {
                var original = el.textContent;
                el.textContent = 'Copied!';
                setTimeout(function () { el.textContent = original; }, 1200);
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(el.dataset.copy).then(done).catch(done);
            } else { done(); }
        });
    });
</script>
@endsection
