@php
    $locale = session('locale', 'en');
    $sidebarLinks = [
        ['route' => 'admin.dashboard', 'label' => 'Strategic Hub', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
        ['route' => 'admin.news.index', 'label' => 'News Archive', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>'],
        ['route' => 'admin.woredas.index', 'label' => 'Woreda Portals', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>'],
        ['route' => 'admin.gallery.index', 'label' => 'Visual Gallery', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
        ['route' => 'admin.tourism.index', 'label' => 'Tourism Hub', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
        ['route' => 'admin.hero.index', 'label' => 'Hero Branding', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>'],
        ['route' => 'admin.service-sectors.index', 'label' => 'Zone Services', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>'],
        ['route' => 'admin.tenders.index', 'label' => 'Tender Registry', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
        ['route' => 'admin.vacancies.index', 'label' => 'Career Roles', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
        ['route' => 'admin.documents.index', 'label' => 'Asset Archive', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>'],
        ['route' => 'admin.projects.index', 'label' => 'Strategic Projects', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>'],
        ['route' => 'admin.leadership.index', 'label' => 'Leadership Hub', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
        ['route' => 'admin.alerts.index', 'label' => 'Emergency Center', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'],
        ['route' => 'admin.investments.index', 'label' => 'Investment Gateway', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>'],
        ['route' => 'admin.directory.index', 'label' => 'Zone Directory', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'],
        ['route' => 'admin.contact.index', 'label' => 'Official Messages', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
        ['route' => 'admin.settings.index', 'label' => 'System Rigging', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'],
        ['route' => 'admin.users.index', 'label' => 'Access Control', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>'],
    ];
@endphp

{{-- Overlay (mobile) --}}
<div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-20 md:hidden" x-show="sidebarOpen"
    @click="sidebarOpen=false" x-transition.opacity style="display:none"></div>

{{-- Sidebar --}}
<aside
    class="fixed md:static inset-y-0 left-0 z-30 w-72 bg-white border-r border-gray-200 flex flex-col shadow-lg md:shadow-none transition-transform duration-300 overflow-hidden"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

    {{-- Logo / Brand --}}
    <div class="h-16 flex items-center gap-3 px-6 border-b border-gray-200 flex-shrink-0">
        <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center text-white font-bold text-lg">O</div>
        <div>
            <p class="font-bold text-gray-900 leading-tight text-base">OSZA Admin</p>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Dashboard</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 custom-scrollbar">
        @foreach($sidebarLinks as $link)
            @php $active = request()->routeIs($link['route'] . '*'); @endphp
            <a href="{{ route($link['route']) }}"
                class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-colors duration-200 {{ $active ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <div class="w-6 h-6 flex items-center justify-center">
                    <svg class="w-5 h-5 flex-shrink-0 {{ $active ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $link['icon'] !!}
                    </svg>
                </div>
                <span>{{ $link['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- User Session Control --}}
    <div class="p-4 bg-gray-50 border-t border-gray-200 mt-auto">
        <div class="flex items-center gap-3 mb-4 px-2">
            <div
                class="w-9 h-9 bg-white text-blue-600 rounded-full flex items-center justify-center font-bold text-sm border border-gray-300 shadow-sm">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-bold text-gray-900 truncate">
                    {{ auth()->user()->name ?? 'Administrator' }}
                </p>
                <p class="text-xs font-semibold text-gray-500 truncate">Admin Access</p>
            </div>
        </div>

        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-100 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 0px;
        background: transparent;
    }
</style>