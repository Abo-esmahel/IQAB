<nav class="sticky top-0 z-40 bg-dark-900/80 backdrop-blur-xl border-b border-dark-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-4">
                @auth
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-dark-400 hover:text-white hover:bg-dark-800">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                @endauth
                <a href="{{ route('home') }}" class="flex items-center gap-2 lg:hidden">
                    <div class="h-8 w-8 rounded-lg bg-primary-600 flex items-center justify-center">
                        <span class="text-white font-bold">Q</span>
                    </div>
                    <span class="text-lg font-bold text-white">IQAB</span>
                </a>
            </div>
            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-medium text-dark-300 hover:text-white transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">Get Started</a>
                @endguest
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-2 rounded-lg hover:bg-dark-800 transition-colors">
                            <img src="{{ asset('images/default-avatar.svg') }}" alt="{{ auth()->user()->name }}"
                                 class="h-8 w-8 rounded-full object-cover ring-1 ring-primary-500/30">
                            <span class="hidden sm:block text-sm text-dark-200">{{ auth()->user()->name }}</span>
                            <svg class="h-4 w-4 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-cloak x-transition x-cloak @click.away="open = false"
                             class="absolute right-0 mt-2 w-56 rounded-xl bg-dark-800 border border-dark-700 shadow-xl py-2 z-50">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-dark-200 hover:bg-dark-700">Profile</a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-dark-200 hover:bg-dark-700">Admin Panel</a>
                            @endif
                            <hr class="border-dark-700 my-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-dark-700">Logout</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>