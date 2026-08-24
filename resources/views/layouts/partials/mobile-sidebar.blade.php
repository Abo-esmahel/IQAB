<div x-show="sidebarOpen" x-cloak class="relative z-50 lg:hidden" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="fixed inset-0 bg-dark-950/80" @click="sidebarOpen = false"></div>
    <div class="fixed inset-y-0 left-0 w-64 bg-dark-900 border-r border-dark-800 z-50">
        @include('layouts.partials.sidebar-nav')
    </div>
</div>
