@php
    $locale = session('locale', 'en');
    $navLinks = [
        ['route' => 'home', 'label' => __('home')],
        ['route' => 'news.index', 'label' => __('news')],
        ['route' => 'tourism.index', 'label' => __('visit')],
        ['route' => 'about.index', 'label' => __('about')],
        ['route' => 'contact.index', 'label' => __('contact')],
    ];
@endphp

<nav class="bg-white shadow-md sticky top-0 z-40" x-data="{ menuOpen: false, langOpen: false }">
    <div class="max-w-[1440px] mx-auto px-4">
        <div class="flex justify-between items-center h-20">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                @if($settings->header_logo)
                    <img src="{{ asset('storage/' . $settings->header_logo) }}" class="h-10 w-auto" alt="Header Logo">
                @else
                    <div
                        class="w-10 h-10 bg-[#1a56db] rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                        O</div>
                @endif
                <div class="flex flex-col leading-tight">
                    <span class="font-bold text-lg text-gray-800">{{ __('title_main') }}</span>
                    <span class="text-xs text-gray-500 font-medium">{{ __('title_sub') }}</span>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center space-x-7">
                @foreach($navLinks as $link)
                    <a href="{{ route($link['route']) }}"
                        class="text-gray-700 hover:text-[#1a56db] font-medium transition-colors {{ request()->routeIs(explode('.', $link['route'])[0] . '*') ? 'text-[#1a56db] font-semibold' : '' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- Right: Search + Login + Lang --}}
            <div class="hidden md:flex items-center space-x-4">
                {{-- Search --}}
                <form action="{{ route('news.index') }}" method="GET" class="relative">
                    <input type="text" name="search" placeholder="{{ __('search') }}"
                        class="pl-3 pr-9 py-1.5 border border-gray-200 rounded-full text-sm focus:outline-none focus:border-[#1a56db] focus:ring-1 focus:ring-[#1a56db] w-40 transition-all focus:w-56"
                        value="{{ request('search') }}">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                        </svg>
                    </button>
                </form>

                {{-- Admin Login --}}
                @auth
                    <a href="{{ route('admin.dashboard') }}"
                        class="bg-[#1a56db]/10 text-[#1a56db] hover:bg-[#1a56db] hover:text-white px-4 py-1.5 rounded-full text-sm font-bold transition-all border border-[#1a56db]/20">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('admin.login') }}"
                        class="bg-[#1a56db]/10 text-[#1a56db] hover:bg-[#1a56db] hover:text-white px-4 py-1.5 rounded-full text-sm font-bold transition-all border border-[#1a56db]/20">
                        {{ __('login') }}
                    </a>
                @endauth

                {{-- Language Switcher --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-1 text-gray-700 hover:text-[#1a56db] transition-colors focus:outline-none text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                        <span class="uppercase">{{ strtoupper($locale) }}</span>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition
                        class="absolute right-0 mt-2 w-36 bg-white border border-gray-100 rounded-xl shadow-lg py-1 z-50">
                        <a href="{{ route('locale.set', 'en') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 {{ $locale === 'en' ? 'font-semibold text-[#1a56db]' : '' }}">English</a>
                        <a href="{{ route('locale.set', 'am') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 {{ $locale === 'am' ? 'font-semibold text-[#1a56db]' : '' }}">አማርኛ</a>
                        <a href="{{ route('locale.set', 'or') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 {{ $locale === 'or' ? 'font-semibold text-[#1a56db]' : '' }}">Afaan
                            Oromo</a>
                    </div>
                </div>
            </div>

            {{-- Mobile Hamburger --}}
            <button @click="menuOpen = !menuOpen" class="md:hidden text-gray-700 focus:outline-none p-2">
                <svg x-show="!menuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="menuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="menuOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        class="md:hidden bg-white border-t px-4 py-4 space-y-3 shadow-inner">
        @foreach($navLinks as $link)
            <a href="{{ route($link['route']) }}" class="block text-gray-700 hover:text-[#1a56db] font-medium py-1"
                @click="menuOpen=false">{{ $link['label'] }}</a>
        @endforeach
        <a href="{{ route('admin.login') }}" class="block text-[#1a56db] font-bold py-1"
            @click="menuOpen=false">{{ __('login') }}</a>
        <div class="pt-3 border-t border-gray-100 flex gap-3">
            <a href="{{ route('locale.set', 'en') }}"
                class="text-sm px-2 py-1 rounded border {{ $locale === 'en' ? 'bg-[#1a56db] text-white border-[#1a56db]' : 'border-gray-200 text-gray-600' }}">EN</a>
            <a href="{{ route('locale.set', 'am') }}"
                class="text-sm px-2 py-1 rounded border {{ $locale === 'am' ? 'bg-[#1a56db] text-white border-[#1a56db]' : 'border-gray-200 text-gray-600' }}">AM</a>
            <a href="{{ route('locale.set', 'or') }}"
                class="text-sm px-2 py-1 rounded border {{ $locale === 'or' ? 'bg-[#1a56db] text-white border-[#1a56db]' : 'border-gray-200 text-gray-600' }}">OR</a>
        </div>
    </div>
</nav>