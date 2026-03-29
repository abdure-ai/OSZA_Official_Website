@extends('layouts.app')
@section('title', __('vacancies') . ' — OSZA')
@section('content')
    @php $locale = session('locale', 'en'); @endphp

    {{-- ═══════════════════════════════════════════ VACANCIES HERO ══ --}}
    <section class="relative bg-blue-900 text-white py-20 md:py-32 overflow-hidden">
        {{-- Decorative Background --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/40 z-10"></div>
            <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?q=80&w=1920&auto=format&fit=crop" 
                 class="w-full h-full object-cover scale-110 opacity-60" alt="Vacancies Hero">
        </div>

        <div class="max-w-[1440px] mx-auto px-4 relative z-20">
            <div class="max-w-3xl">
                <span class="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                    {{ __('join_our_team') }}
                </span>
                <h1 class="text-5xl md:text-7xl font-black mb-4 leading-none antialiased drop-shadow-2xl italic tracking-tight">
                    {{ __('vacancies') }}
                </h1>
                <p class="text-lg md:text-xl text-gray-200 font-medium opacity-90">{{ __('careers_subtitle') }}</p>
            </div>
        </div>
        {{-- Bottom fade --}}
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    <div class="max-w-[1440px] mx-auto px-4 py-10">
        <div class="space-y-4">
            @forelse($vacancies as $vacancy)
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition p-6">
                    <div class="flex flex-col md:flex-row md:items-start gap-4">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900 text-lg mb-1">
                                {{ $vacancy->{'title_' . $locale} ?? $vacancy->title_en }}</h3>
                            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 mb-3">
                                @if($vacancy->department)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $vacancy->department }}
                                    </span>
                                @endif
                                @if($vacancy->deadline)
                                    <span class="flex items-center gap-1 text-amber-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ __('deadline_label') }}
                                        {{ $vacancy->deadline instanceof \Carbon\Carbon ? $vacancy->deadline->format('M d, Y') : $vacancy->deadline }}
                                    </span>
                                @endif
                            </div>
                            @if($vacancy->description_en)
                                <p class="text-sm text-gray-600 line-clamp-3">
                                    {{ $vacancy->{'description_' . $locale} ?? $vacancy->description_en }}</p>
                            @endif
                        </div>
                        <div class="flex-shrink-0 flex flex-col sm:flex-row md:flex-col gap-3 min-w-[180px]">
                            <a href="{{ route('vacancies.show', $vacancy->id) }}" 
                               class="w-full text-center px-6 py-3 bg-white text-blue-900 border-2 border-blue-900 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-50 transition active:scale-95 shadow-sm">
                                {{ __('view_detail') }}
                            </a>
                            @if($vacancy->document_url)
                                <a href="{{ asset($vacancy->document_url) }}" target="_blank" download
                                    class="w-full text-center px-6 py-3 bg-blue-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-[#f5a623] hover:text-blue-900 transition active:scale-95 shadow-lg flex items-center justify-center gap-2">
                                    {{ __('apply_now') }}
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 text-gray-400">{{ __('no_vacancies') }}</div>
            @endforelse
        </div>
        <div class="mt-8">{{ $vacancies->links() }}</div>
    </div>
@endsection