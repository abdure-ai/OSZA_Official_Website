@extends('layouts.app')
@section('title', __('about') . ' — OSZA')
@section('content')
    @php $locale = session('locale', 'en'); @endphp

    {{-- ═══════════════════════════════════════════ ABOUT HERO ══ --}}
    <section class="relative h-[60vh] md:h-[80vh] bg-black overflow-hidden" x-data="{ 
        activeSlide: 0, 
        slidesCount: {{ $heroSlides->count() ?: 1 }},
        next() { this.activeSlide = (this.activeSlide + 1) % this.slidesCount },
        prev() { this.activeSlide = (this.activeSlide - 1 + this.slidesCount) % this.slidesCount }
    }" x-init="setInterval(() => next(), 8000)">
        
        @forelse($heroSlides as $index => $slide)
            <div x-show="activeSlide === {{ $index }}" 
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 scale-110"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-1000"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute inset-0 z-0">
                
                @if($slide->media_type === 'video')
                    <video src="{{ asset($slide->media_url) }}" autoplay muted loop playsinline class="w-full h-full object-cover"></video>
                @else
                    <img src="{{ asset($slide->media_url) }}" class="w-full h-full object-cover" alt="{{ $slide->title_en }}">
                @endif
                <div class="absolute inset-0 bg-gradient-to-r from-blue-950/80 via-blue-900/40 to-transparent z-10"></div>
                
                <div class="absolute inset-0 flex items-center z-20">
                    <div class="max-w-[1440px] mx-auto px-4 w-full">
                        <div class="max-w-3xl" x-show="activeSlide === {{ $index }}" x-transition:enter="transition ease-out duration-700 delay-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                            <span class="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6">
                                {{ session('locale') === 'am' ? 'ስለ እኛ' : (session('locale') === 'or' ? "Waa'ee Keenya" : "About Us") }}
                            </span>
                            <h1 class="text-5xl md:text-8xl font-black text-white mb-6 leading-none antialiased drop-shadow-2xl">
                                {{ $slide->{'title_' . $locale} ?? $slide->title_en }}
                            </h1>
                            <p class="text-xl md:text-2xl text-gray-100 font-medium leading-relaxed drop-shadow-lg opacity-90">
                                {{ $slide->{'subtitle_' . $locale} ?? $slide->subtitle_en }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- Fallback static hero if no slides --}}
            <div class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1523050335456-1ccfa5277cc2?q=80&w=1920&auto=format&fit=crop" class="w-full h-full object-cover" alt="Fallback Hero">
                <div class="absolute inset-0 bg-blue-900/60 z-10"></div>
                <div class="absolute inset-0 flex items-center z-20">
                    <div class="max-w-[1440px] mx-auto px-4">
                        <h1 class="text-6xl font-black text-white uppercase tracking-tighter">About OSZA</h1>
                    </div>
                </div>
            </div>
        @endforelse

        {{-- Slide Indicators --}}
        @if($heroSlides->count() > 1)
            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-30 flex gap-3">
                @foreach($heroSlides as $index => $slide)
                    <button @click="activeSlide = {{ $index }}" 
                        :class="activeSlide === {{ $index }} ? 'w-12 bg-[#f5a623]' : 'w-3 bg-white/50'"
                        class="h-1.5 rounded-full transition-all duration-500"></button>
                @endforeach
            </div>
        @endif
    </section>

    {{-- ════════════════════════════════════════ ZONE STATISTICS ══ --}}
    <section class="max-w-[1440px] mx-auto px-4 -mt-16 relative z-30">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-[2rem] p-8 shadow-2xl border border-gray-100 flex flex-col items-center text-center group hover:bg-blue-900 transition-colors duration-500">
                <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-2 group-hover:text-blue-200 transition-colors">Total Population</span>
                <span class="text-3xl md:text-4xl font-black text-gray-900 group-hover:text-white transition-colors tracking-tighter">
                    {{ number_format($woredaStats['total_population'] / 1000000, 1) }}M+
                </span>
            </div>
            <div class="bg-white rounded-[2rem] p-8 shadow-2xl border border-gray-100 flex flex-col items-center text-center group hover:bg-blue-900 transition-colors duration-500">
                <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-2 group-hover:text-blue-200 transition-colors">Total Area</span>
                <span class="text-3xl md:text-4xl font-black text-gray-900 group-hover:text-white transition-colors tracking-tighter">
                    {{ number_format($woredaStats['total_area']) }} <small class="text-xs">KM²</small>
                </span>
            </div>
            <div class="bg-white rounded-[2rem] p-8 shadow-2xl border border-gray-100 flex flex-col items-center text-center group hover:bg-blue-900 transition-colors duration-500">
                <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-2 group-hover:text-blue-200 transition-colors">Woredas</span>
                <span class="text-3xl md:text-4xl font-black text-gray-900 group-hover:text-white transition-colors tracking-tighter">
                    {{ $woredaStats['woreda_count'] }}
                </span>
            </div>
            <div class="bg-white rounded-[2rem] p-8 shadow-2xl border border-gray-100 flex flex-col items-center text-center group hover:bg-blue-900 transition-colors duration-500">
                <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-2 group-hover:text-blue-200 transition-colors">Legacy Since</span>
                <span class="text-3xl md:text-4xl font-black text-gray-900 group-hover:text-white transition-colors tracking-tighter">
                    {{ $woredaStats['established_since'] }}
                </span>
            </div>
        </div>
    </section>

    <div class="max-w-[1440px] mx-auto px-4 py-20 space-y-24">
        
        {{-- ── Dynamic About Sections ── --}}
        @foreach($sections as $section)
            @if($section->type === 'history')
                <section class="grid md:grid-cols-2 gap-16 items-center">
                    <div class="relative">
                        <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-60"></div>
                        <h2 class="text-5xl md:text-6xl font-black text-gray-900 mb-8 uppercase tracking-tighter leading-none">
                            {{ $section->{'title_' . $locale} ?? $section->title_en }}
                        </h2>
                        <div class="prose prose-lg text-gray-500 font-medium leading-relaxed">
                            {!! nl2br(e($section->{'content_' . $locale} ?? $section->content_en)) !!}
                        </div>
                    </div>
                    @if($section->image_url)
                        <div class="rounded-[3rem] overflow-hidden shadow-2xl border-8 border-white transform rotate-2 hover:rotate-0 transition-transform duration-700">
                            <img src="{{ asset($section->image_url) }}" class="w-full h-[500px] object-cover" alt="History">
                        </div>
                    @else
                        <div class="bg-blue-50 rounded-[3rem] aspect-square flex items-center justify-center border-4 border-dashed border-blue-100">
                            <svg class="w-32 h-32 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                    @endif
                </section>
            @endif
        @endforeach

        {{-- Mission & Vision Row --}}
        <section class="grid md:grid-cols-2 gap-10">
            @foreach($sections as $section)
                @if(in_array($section->type, ['mission', 'vision']))
                    <div class="group bg-white rounded-[2.5rem] p-12 shadow-sm border border-gray-100 hover:shadow-2xl transition-all duration-500 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-2 h-full {{ $section->type === 'mission' ? 'bg-[#1a56db]' : 'bg-[#f5a623]' }}"></div>
                        <div class="w-20 h-20 {{ $section->type === 'mission' ? 'bg-blue-50 text-blue-600' : 'bg-orange-50 text-orange-600' }} rounded-3xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                            @if($section->type === 'mission')
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            @else
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            @endif
                        </div>
                        <h2 class="text-4xl font-black text-gray-900 mb-6 uppercase tracking-tighter">{{ $section->{'title_' . $locale} ?? $section->title_en }}</h2>
                        <p class="text-gray-500 text-lg leading-relaxed font-medium">
                            {{ $section->{'content_' . $locale} ?? $section->content_en }}
                        </p>
                    </div>
                @endif
            @endforeach
        </section>

        {{-- Other Sections --}}
        @foreach($sections as $section)
            @if(!in_array($section->type, ['history', 'mission', 'vision']))
                <section class="max-w-4xl mx-auto text-center py-10">
                    <h2 class="text-4xl font-black text-gray-900 mb-8 uppercase tracking-tighter">{{ $section->{'title_' . $locale} ?? $section->title_en }}</h2>
                    <div class="text-gray-500 text-lg leading-relaxed font-medium">
                        {!! nl2br(e($section->{'content_' . $locale} ?? $section->content_en)) !!}
                    </div>
                </section>
            @endif
        @endforeach

        {{-- Leadership --}}
        @if($leadership->isNotEmpty())
            <section class="py-10">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4 uppercase tracking-tighter">The Leadership Council</h2>
                    <div class="h-1.5 w-24 bg-[#1a56db] rounded-full mx-auto"></div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                    @foreach($leadership as $leader)
                        <div class="group bg-white rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500 p-8 flex flex-col items-center text-center transform hover:-translate-y-2">
                            <div class="relative mb-8">
                                <div class="absolute inset-0 bg-[#1a56db] rounded-full blur-xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                                @if($leader->photo_url)
                                    <img src="{{ asset($leader->photo_url) }}"
                                        alt="{{ $leader->{'name_' . $locale} ?? $leader->name_en }}"
                                        class="w-40 h-40 rounded-full object-cover border-4 border-white shadow-xl relative z-10 grayscale group-hover:grayscale-0 transition-all duration-500">
                                @else
                                    <div class="w-40 h-40 rounded-full bg-gray-50 flex items-center justify-center border-4 border-white shadow-xl relative z-10 text-[#1a56db]">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    </div>
                                @endif
                            </div>
                            <h3 class="font-black text-gray-900 text-xl leading-tight mb-2">{{ $leader->{'name_' . $locale} ?? $leader->name_en }}</h3>
                            <div class="h-0.5 w-8 bg-gray-100 group-hover:w-16 group-hover:bg-[#f5a623] transition-all duration-500 mb-4"></div>
                            <p class="text-[10px] font-black text-[#1a56db] uppercase tracking-[0.2em] px-4 py-1.5 bg-blue-50 rounded-full">
                                {{ $leader->{'title_' . $locale} ?? $leader->title_en }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection