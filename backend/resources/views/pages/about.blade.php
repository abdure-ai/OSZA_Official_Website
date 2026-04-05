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
    <section class="relative z-20 -mt-16 md:-mt-24 max-w-7xl mx-auto px-4">
        <div class="bg-gray-900 rounded-[3rem] p-10 md:p-14 shadow-2xl overflow-hidden relative border border-white/10 transition hover:border-[#f5a623]/30">
            <div class="absolute top-0 right-0 p-20 opacity-5 pointer-events-none">
                <svg class="w-64 h-64 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-12 relative z-10">
                @php
                    $statsItems = [
                        ['label' => 'Total Residents', 'value' => number_format($stats['total_population'] ?? 0), 'icon' => '👥', 'text' => 'People'],
                        ['label' => 'Zone Territory', 'value' => number_format($stats['total_area'] ?? 0), 'icon' => '🗺️', 'text' => 'KM²'],
                        ['label' => 'Administration Units', 'value' => $stats['woreda_count'] ?? 0, 'icon' => '🏛️', 'text' => 'Woredas'],
                        ['label' => 'Established Since', 'value' => $stats['earliest_year'] ?? 'N/A', 'icon' => '📜', 'text' => 'Heritage'],
                    ];
                @endphp
                @foreach($statsItems as $item)
                    <div class="text-center group">
                        <div class="text-4xl mb-4 transform group-hover:scale-125 transition duration-500">{{ $item['icon'] }}</div>
                        <div class="text-4xl font-black text-white tracking-tighter">{{ $item['value'] }}</div>
                        <div class="text-[10px] font-black text-blue-400 uppercase tracking-widest mt-2">{{ $item['label'] }}</div>
                        <div class="text-xs text-gray-500 mt-1 font-bold">{{ $item['text'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ HISTORY SECTION ══ --}}
    @php $history = $sections->where('type', 'history')->first(); @endphp
    @if($history)
        <section class="max-w-7xl mx-auto px-4 py-32 grid grid-cols-1 lg:grid-cols-2 gap-24 items-center">
            <div class="relative group">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-60"></div>
                <div class="relative rounded-[4rem] overflow-hidden shadow-2xl h-[600px] border-8 border-gray-50">
                    <img src="{{ $history->image_url ? asset($history->image_url) : asset('images/about-history.jpg') }}" class="w-full h-full object-cover transition duration-1000 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-900/40 to-transparent"></div>
                </div>
            </div>
            <div class="space-y-8">
                <span class="px-5 py-2 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest">About the Zone</span>
                <h2 class="text-5xl md:text-7xl font-black text-gray-900 tracking-tighter uppercase leading-[0.95]">
                    {{ $history->{'title_'.$locale} ?? $history->title_en }}
                </h2>
                <div class="text-xl text-gray-500 leading-relaxed font-medium">
                    {!! nl2br(e($history->{'content_'.$locale} ?? $history->content_en)) !!}
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════════════════════ MISSION & VISION ══ --}}
    <section class="bg-gray-50 py-32 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">
                @php 
                    $mission = $sections->where('type', 'mission')->first();
                    $vision = $sections->where('type', 'vision')->first();
                @endphp
                
                {{-- Mission Card (Large) --}}
                @if($mission)
                <div class="lg:col-span-3 bg-white p-14 rounded-[4rem] shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-500 relative group min-h-[400px] flex flex-col justify-center">
                    <div class="absolute top-10 right-10 text-8xl font-black text-blue-50/50 uppercase tracking-tighter group-hover:text-blue-100/50 transition">Mission</div>
                    <div class="w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-3xl mb-8 shadow-xl shadow-blue-200">🎯</div>
                    <h3 class="text-4xl font-black text-gray-900 tracking-tighter uppercase mb-6 relative z-10">{{ $mission->{'title_'.$locale} ?? $mission->title_en }}</h3>
                    <p class="text-xl text-gray-500 font-medium leading-relaxed relative z-10 max-w-2xl">
                        {{ $mission->{'content_'.$locale} ?? $mission->content_en }}
                    </p>
                </div>
                @endif

                {{-- Vision Card --}}
                @if($vision)
                <div class="lg:col-span-2 bg-[#1a56db] p-14 rounded-[4rem] shadow-xl border border-blue-400/20 hover:shadow-2xl transition-all duration-500 relative group text-white min-h-[400px] flex flex-col justify-center">
                    <div class="absolute top-10 right-10 text-6xl font-black text-white/5 uppercase tracking-tighter group-hover:text-white/10 transition">Vision</div>
                    <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl mb-8 border border-white/20">👁️</div>
                    <h3 class="text-4xl font-black tracking-tighter uppercase mb-6 relative z-10">{{ $vision->{'title_'.$locale} ?? $vision->title_en }}</h3>
                    <p class="text-lg text-white/90 font-medium leading-relaxed relative z-10">
                        {{ $vision->{'content_'.$locale} ?? $vision->content_en }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ STRATEGIC OBJECTIVES ══ --}}
    @php $objectives = $sections->whereIn('type', ['objective', 'general'])->sortBy('sort_order'); @endphp
    @if($objectives->count() > 0)
    <section class="py-32 max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-20 gap-8">
            <div class="space-y-4">
                <span class="text-blue-600 font-black uppercase tracking-widest text-[10px]">Development Roadmap</span>
                <h2 class="text-5xl md:text-6xl font-black text-gray-900 tracking-tighter uppercase leading-none">Strategic Objectives</h2>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($objectives as $index => $obj)
                <div class="bg-white p-12 rounded-[3.5rem] border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-4 transition-all duration-500 group relative overflow-hidden h-full flex flex-col">
                    <span class="absolute -top-4 -right-4 text-8xl font-black text-gray-50 group-hover:text-blue-50 transition group-hover:scale-110 duration-700">0{{ $loop->iteration }}</span>
                    <div class="relative z-10 space-y-6 flex-1">
                        <div class="w-12 h-1 px-4 bg-blue-600 rounded-full mb-6 group-hover:w-24 transition-all duration-500"></div>
                        <h4 class="text-2xl font-black text-gray-900 leading-tight uppercase group-hover:text-blue-700 transition">{{ $obj->{'title_'.$locale} ?? $obj->title_en }}</h4>
                        <p class="text-gray-500 font-medium leading-relaxed">
                            {{ $obj->{'content_'.$locale} ?? $obj->content_en }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ═══════════════════════════════════════════ LEADERSHIP ══ --}}
    @if($leadership->isNotEmpty())
        <section class="bg-gray-900 py-32 rounded-[5rem] mx-4 my-10 overflow-hidden relative">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/grid-me.png')] opacity-10"></div>
            <div class="max-w-7xl mx-auto px-4 relative z-10">
                <div class="text-center mb-24">
                    <span class="text-blue-400 font-black uppercase tracking-widest text-[10px]">Governance Structure</span>
                    <h2 class="text-5xl font-black text-white tracking-tighter uppercase mt-4">The Leadership Council</h2>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
                    @foreach($leadership as $leader)
                        <div class="group bg-gray-800/50 backdrop-blur-md rounded-[3.5rem] p-10 flex flex-col items-center text-center hover:bg-gray-800 transition-all duration-500 border border-gray-700/50 hover:border-blue-500/50 transform hover:-translate-y-4">
                            <div class="relative mb-8">
                                <div class="absolute inset-0 bg-blue-600 rounded-full blur-2xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                                @if($leader->photo_url)
                                    <img src="{{ asset($leader->photo_url) }}"
                                        alt="{{ $leader->{'name_' . $locale} ?? $leader->name_en }}"
                                        class="w-48 h-48 rounded-[2.5rem] object-cover border-4 border-gray-700 shadow-2xl relative z-10 grayscale group-hover:grayscale-0 transition-all duration-500">
                                @else
                                    <div class="w-48 h-48 rounded-[2.5rem] bg-gray-700 flex items-center justify-center border-4 border-gray-600 shadow-2xl relative z-10 text-blue-400">
                                        <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    </div>
                                @endif
                            </div>
                            <h3 class="font-black text-white text-2xl tracking-tighter leading-tight mb-2 uppercase">{{ $leader->{'name_' . $locale} ?? $leader->name_en }}</h3>
                            <p class="text-[10px] font-black text-blue-400 uppercase tracking-[0.25em] px-5 py-2 bg-blue-900/50 rounded-full mt-4">
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