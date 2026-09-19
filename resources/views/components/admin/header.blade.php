@props([
    'title' => '',
    'subtitle' => null,
    'icon' => null,
    'backRoute' => null,
    'backLabel' => null,
    'action' => null,
])
<div class="mb-8">
    @if($backRoute)
        <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-1.5 text-sm mb-4">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 text-dark-500 hover:text-primary-400 transition-colors">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <svg class="h-3.5 w-3.5 text-dark-600 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route($backRoute) }}" class="text-dark-400 hover:text-primary-400 transition-colors">{{ $backLabel ?? $title }}</a>
            <svg class="h-3.5 w-3.5 text-dark-600 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-primary-400" aria-current="page">{{ $title }}</span>
        </nav>
    @endif

    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            @if($icon)
                <div class="hidden sm:flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-primary-500/20 bg-primary-500/10">
                    <svg class="h-5 w-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
                </div>
            @endif
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $title }}</h1>
                @if($subtitle)
                    <p class="text-sm text-dark-500 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        @if($action)
            <div class="shrink-0">{{ $action }}</div>
        @endif
    </div>
</div>