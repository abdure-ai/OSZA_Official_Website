<header
    class="h-20 bg-white border-b-2 border-gray-50 flex items-center justify-between px-10 flex-shrink-0 shadow-sm z-10">
    {{-- Mobile: hamburger + page title --}}
    <div class="flex items-center gap-6">
        <button @click="sidebarOpen = !sidebarOpen"
            class="md:hidden w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 hover:text-blue-600 rounded-xl transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <h1 class="text-xl font-black text-gray-900 tracking-tighter italic">
            @yield('page-title', 'Administrative Terminal')</h1>
    </div>

    {{-- Right: visit site link + user avatar --}}
    <div class="flex items-center gap-6">
        <a href="{{ route('home') }}" target="_blank"
            class="hidden sm:flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-blue-600 transition-all group">
            <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
            Public Interface
        </a>
        <div
            class="w-10 h-10 bg-blue-600 rounded-2xl flex items-center justify-center text-white text-sm font-black italic shadow-lg shadow-blue-500/30">
            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
        </div>
    </div>
</header>