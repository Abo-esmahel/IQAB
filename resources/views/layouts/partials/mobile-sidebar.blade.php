<div x-show="sidebarOpen" x-cloak class="relative z-50 lg:hidden" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="fixed inset-0 bg-dark-950/80" @click="sidebarOpen = false"></div>
    <div class="fixed inset-y-0 left-0 w-64 bg-dark-900 border-r border-dark-800 z-50">
        <div class="flex h-16 items-center justify-between px-6 border-b border-dark-800">
            <div class="flex items-center gap-2">
                <div class="h-8 w-8 rounded-lg bg-primary-600 flex items-center justify-center">
                    <span class="text-white font-bold">Q</span>
                </div>
                <span class="text-xl font-bold text-white">IQAB</span>
            </div>
            <button @click="sidebarOpen = false" class="p-2 text-dark-400 hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @include('layouts.partials.sidebar')
    </div>
</div>