<a href="{{ route('services.show', $service) }}" class="group relative overflow-hidden rounded-2xl border border-dark-800 bg-dark-900 transition-all hover:-translate-y-0.5 hover:border-primary-500/50 hover:shadow-lg hover:shadow-primary-900/20">
    @if($service->image)
        <div class="relative h-36 overflow-hidden bg-dark-800">
            <img src="{{ $service->image }}" alt="{{ $service->name }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-dark-900 to-transparent"></div>
        </div>
    @else
        <div class="relative flex h-36 items-center justify-center overflow-hidden bg-gradient-to-br from-primary-900/30 to-dark-800">
            <svg class="h-12 w-12 text-primary-500/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <div class="absolute inset-0 bg-gradient-to-t from-dark-900 to-transparent"></div>
        </div>
    @endif

    <div class="p-4">
        <div class="flex items-start justify-between gap-2">
            <h3 class="font-semibold text-white transition-colors group-hover:text-primary-400">{{ $service->name }}</h3>
            <span class="shrink-0 text-sm font-bold text-primary-400">{{ number_format($service->price, 2) }} SAR</span>
        </div>

        @if($service->short_description)
            <p class="mt-1 line-clamp-2 text-xs text-dark-400">{{ $service->short_description }}</p>
        @endif

        @if($service->category)
            <span class="mt-3 inline-flex items-center rounded-full bg-dark-800 px-2 py-0.5 text-xs font-medium text-dark-300">{{ $service->category }}</span>
        @endif
    </div>
</a>
