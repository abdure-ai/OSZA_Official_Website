@extends('layouts.app')

@php
    $locale = session('locale', 'en');
@endphp

@section('title', 'About Oromo Special Zone')

@section('content')
<div class="bg-white overflow-hidden">
    {{-- ═══════════════════════════════════════════ HERO SLIDER (Alpine.js) ══ --}}
    @php 
        $heroSlidesData = $heroSlides->map(function($s) {
            $s->media_url = $s->media_url ? asset($s->media_url) : null;
            return $s;
        });
    @endphp
    @if($heroSlides->count() > 0)
        <section class="relative h-[65vh] md:h-[85vh] bg-[#1a56db] text-white overflow-hidden" 
            x-data="{
                current: 0,
                slides: @js($heroSlidesData),
                isAnimating: false,
                timer: null,
                goTo(i) {
                    if(this.isAnimating) return;
                    this.isAnimating = true;
                    this.current = (i + this.slides.length) % this.slides.length;
                    setTimeout(() => this.isAnimating = false, 500);
                    this.resetTimer();
                },
                next() { this.goTo(this.current + 1); },
                prev() { this.goTo(this.current - 1); },
                resetTimer() { clearTimeout(this.timer); this.timer = setTimeout(() => this.next(), 8000); },
                init() { if(this.slides.length > 1) this.resetTimer(); }
            }">

            {{-- Background media --}}
            <template x-for="(slide, i) in slides" :key="i">
                <div x-show="current === i" class="absolute inset-0 transition-opacity duration-700"
                    :class="isAnimating ? 'opacity-0' : 'opacity-100'">
                    <template x-if="slide.media_url && (slide.media_type === 'video' || slide.media_url.match(/\.(mp4|webm|ogg|mov)$/i))">
                        <video :src="slide.media_url" class="absolute inset-0 w-full h-full object-cover" autoplay muted loop playsinline></video>
                    </template>
                    <template x-if="slide.media_url && slide.media_type !== 'video' && !slide.media_url.match(/\.(mp4|webm|ogg|mov)$/i)">
                        <img :src="slide.media_url" class="absolute inset-0 w-full h-full object-cover">
                    </template>
                    <div class="absolute inset-0 bg-black/40"></div>
                </div>
            </template>

            {{-- Content --}}
            <div class="max-w-7xl mx-auto px-4 h-full flex items-center relative z-10">
                <template x-for="(slide, i) in slides" :key="i">
                    <div x-show="current === i" 
                        x-transition:enter="transition ease-out duration-700 delay-300"
                        x-transition:enter-start="opacity-0 translate-y-12" 
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="max-w-4xl space-y-6">
                        <h1 class="text-5xl md:text-8xl font-black uppercase tracking-tighter leading-[0.95] drop-shadow-2xl antialiased"
                            x-text="slide['title_{{ $locale }}'] || slide.title_en"></h1>
                        <p class="text-xl md:text-2xl text-gray-100/90 font-medium max-w-2xl drop-shadow-lg"
                            x-text="slide['subtitle_{{ $locale }}'] || slide.subtitle_en"></p>
                    </div>
                </template>
            </div>

            {{-- Controls --}}
            @if($heroSlides->count() > 1)
                <div class="absolute bottom-10 left-0 right-0 z-20">
                    <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
                        <div class="flex gap-3">
                            <template x-for="(s, i) in slides" :key="i">
                                <button @click="goTo(i)" 
                                    class="h-1.5 transition-all duration-500 rounded-full"
                                    :class="current === i ? 'w-12 bg-white' : 'w-4 bg-white/30 hover:bg-white/50'"></button>
                            </template>
                        </div>
                        <div class="flex gap-4">
                            <button @click="prev()" class="w-12 h-12 rounded-full border border-white/20 bg-white/10 backdrop-blur hover:bg-white hover:text-blue-900 transition flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button @click="next()" class="w-12 h-12 rounded-full border border-white/20 bg-white/10 backdrop-blur hover:bg-white hover:text-blue-900 transition flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </section>
    @endif

    {{-- ═══════════════════════════════════════════ STATS STRIP ══ --}}
    <section class="relative z-20 -mt-10 md:-mt-16 max-w-[1440px] mx-auto px-4">
        <div class="bg-gray-900 rounded-[2.5rem] p-8 md:p-12 shadow-2xl relative border border-white/10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                @php
                    $statsItems = [
                        ['label' => 'Total Residents', 'value' => number_format($stats['total_population'] ?? 0), 'icon' => '👥'],
                        ['label' => 'Zone Territory', 'value' => number_format($stats['total_area'] ?? 0), 'icon' => '🗺️'],
                        ['label' => 'Administration Units', 'value' => $stats['woreda_count'] ?? 0, 'icon' => '🏛️'],
                        ['label' => 'Established Since', 'value' => $stats['earliest_year'] ?? 'N/A', 'icon' => '📜'],
                    ];
                @endphp
                @foreach($statsItems as $item)
                    <div class="text-center">
                        <div class="text-3xl font-black text-white tracking-tighter">{{ $item['value'] }}</div>
                        <div class="text-[9px] font-black text-blue-400 uppercase tracking-widest mt-1">{{ $item['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ HISTORY SECTION (Tourism Style) ══ --}}
    @php $history = $sections->where('type', 'history')->first(); @endphp
    @if($history)
        <section class="py-24 bg-white relative">
            <div class="max-w-[1440px] mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-start">
                    <div class="space-y-8">
                        <div class="inline-block px-5 py-2 bg-blue-50 text-blue-600 text-xs font-black uppercase tracking-widest rounded-full">
                            Historical Background
                        </div>
                        <h2 class="text-3xl md:text-5xl font-black text-gray-900 leading-tight">
                            {{ $history->{'title_'.$locale} ?? $history->title_en }}
                        </h2>
                        <div class="prose prose-xl text-gray-600 font-medium leading-relaxed max-w-none antialiased whitespace-pre-line">
                            {!! nl2br(e($history->{'content_'.$locale} ?? $history->content_en)) !!}
                        </div>
                    </div>

                    <div class="sticky top-24">
                        <div class="rounded-3xl overflow-hidden shadow-2xl h-[400px] md:h-[600px] border-4 border-white group">
                            <img src="{{ $history->image_url ? asset($history->image_url) : asset('images/about-history.jpg') }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                alt="{{ $history->title_en }}">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════════════════════ MISSION & VISION (Simple Cards) ══ --}}
    @php 
        $mission = $sections->where('type', 'mission')->first();
        $vision = $sections->where('type', 'vision')->first();
    @endphp
    @if($mission || $vision)
    <section class="bg-gray-50 py-24 border-y border-gray-100">
        <div class="max-w-[1440px] mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                @if($mission)
                    <div class="bg-white p-12 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-3xl mb-8 shadow-xl shadow-blue-200">🎯</div>
                        <h3 class="text-3xl font-black text-gray-900 tracking-tighter uppercase mb-6">{{ $mission->{'title_'.$locale} ?? $mission->title_en }}</h3>
                        <p class="text-lg text-gray-600 font-medium leading-relaxed">
                            {{ $mission->{'content_'.$locale} ?? $mission->content_en }}
                        </p>
                    </div>
                @endif

                @if($vision)
                    <div class="bg-white p-12 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-amber-500 text-white rounded-2xl flex items-center justify-center text-3xl mb-8 shadow-xl shadow-amber-200">👁️</div>
                        <h3 class="text-3xl font-black text-gray-900 tracking-tighter uppercase mb-6">{{ $vision->{'title_'.$locale} ?? $vision->title_en }}</h3>
                        <p class="text-lg text-gray-600 font-medium leading-relaxed">
                            {{ $vision->{'content_'.$locale} ?? $vision->content_en }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- ═══════════════════════════════════════════ STRATEGIC OBJECTIVES (Titles Only) ══ --}}
    @php $objectives = $sections->whereIn('type', ['objective', 'general'])->sortBy('sort_order'); @endphp
    @if($objectives->count() > 0)
    <section class="py-24 max-w-[1440px] mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-blue-600 font-black uppercase tracking-widest text-[10px]">Development Roadmap</span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tighter uppercase mt-4">Strategic Objectives</h2>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($objectives as $obj)
                <div class="group bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-blue-200 transition-all duration-300 flex items-center gap-6">
                    <div class="w-12 h-12 flex-shrink-0 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center font-black text-lg group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        {{ $loop->iteration }}
                    </div>
                    <h4 class="text-lg font-black text-gray-800 leading-tight group-hover:text-blue-700 transition">{{ $obj->{'title_'.$locale} ?? $obj->title_en }}</h4>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ═══════════════════════════════════════════ LEADERSHIP COUNCIL ══ --}}
    @if($leadership->isNotEmpty())
        <section class="py-24 bg-gray-900">
            <div class="max-w-[1440px] mx-auto px-4">
                <div class="text-center mb-20">
                    <span class="text-blue-400 font-black uppercase tracking-widest text-[10px]">Governance</span>
                    <h2 class="text-4xl font-black text-white tracking-tighter uppercase mt-4">Leadership Council</h2>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    @foreach($leadership as $leader)
                        <div class="group flex flex-col items-center text-center">
                            <div class="w-40 h-40 md:w-56 md:h-56 rounded-3xl overflow-hidden shadow-2xl mb-6 grayscale hover:grayscale-0 transition-all duration-500 border-4 border-gray-800 group-hover:border-blue-500">
                                @if($leader->photo_url)
                                    <img src="{{ asset($leader->photo_url) }}" alt="{{ $leader->name_en }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-800 flex items-center justify-center text-blue-400">
                                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                                    </div>
                                @endif
                            </div>
                            <h3 class="font-black text-white text-lg tracking-tight leading-tight uppercase">{{ $leader->{'name_' . $locale} ?? $leader->name_en }}</h3>
                            <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest mt-2 px-3 py-1 bg-blue-900/50 rounded-full">
                                {{ $leader->{'position_' . $locale} ?? $leader->position_en }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
@endsection