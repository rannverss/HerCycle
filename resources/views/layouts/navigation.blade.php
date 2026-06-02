<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md border-b border-pink-100 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                    <span class="text-2xl group-hover:scale-110 transition-transform duration-300">🌸</span>
                    <span class="text-xl font-bold bg-gradient-to-r from-pink-400 to-pink-300 bg-clip-text text-transparent">HerCycle</span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden sm:flex sm:items-center sm:ml-8 space-x-1">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span>🏠</span>
                        <span>Home</span>
                    </a>
                    <a href="{{ route('insights.index') }}"
                       class="nav-link {{ request()->routeIs('insights.*') ? 'active' : '' }}">
                        <span>💡</span>
                        <span>Insights</span>
                    </a>
                    <a href="{{ route('profile.edit') }}"
                       class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <span>👤</span>
                        <span>Profile</span>
                    </a>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="hidden sm:flex sm:items-center">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 px-4 py-2 rounded-xl hover:bg-pink-50 transition-all duration-200">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-300 to-pink-200 flex items-center justify-center text-white font-semibold text-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="text-her-text font-medium">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 text-her-text-light transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-cute-lg border border-pink-100 py-2 z-50" style="display: none;">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-her-text hover:bg-pink-50 transition-colors">
                            👤 Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-her-text hover:bg-pink-50 transition-colors">
                                🚪 Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 rounded-xl text-her-text hover:bg-pink-50 transition-all">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-pink-100 bg-white">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                🏠 Home
            </a>
            <a href="{{ route('insights.index') }}" class="nav-link {{ request()->routeIs('insights.*') ? 'active' : '' }}">
                💡 Insights
            </a>
            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                👤 Profile
            </a>
        </div>
        <div class="px-4 py-3 border-t border-pink-100">
            <p class="text-sm text-her-text-light mb-2">{{ Auth::user()->name }}</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link text-pink-500 w-full">
                    🚪 Log Out
                </button>
            </form>
        </div>
    </div>
</nav>
