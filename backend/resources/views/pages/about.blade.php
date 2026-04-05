@extends('layouts.app')

@php
    $locale = session('locale', 'en');
@endphp

@section('title', 'About Oromo Special Zone')

@section('content')
<div class="bg-white overflow-hidden" x-data="{ 
    activeModal: null,
    scrollPos: 0
}" @scroll.window="scrollPos = window.pageYOffset">
    
    {{-- 1. Hero Dynamic Slider --}}
    @if($heroSlides->count() > 0)
        <section class="relative h-[85vh] w-full overflow-hidden group">
            <div class="swiper mainHeroSwiper h-full w-full">
                <div class="swiper-wrapper">
                    @foreach($heroSlides as $slide)
                        <div class="swiper-slide relative h-full w-full">
                            @if($slide->media_type === 'video')
                                <video src="{{ asset($slide->media_url) }}" class="absolute inset-0 w-full h-full object-cover" autoplay muted loop playsinline></video>
                            @else
                                <img src="{{ asset($slide->media_url) }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $slide->{'title_'.$locale} }}">
                            @endif
                            
                            {{-- Overlay Content --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex items-center justify-center text-center p-6">
                                <div class="max-w-4xl space-y-6">
                                    <h1 class="text-6xl md:text-8xl font-black text-white uppercase tracking-tighter leading-none animate-fade-up">
                                        {{ $slide->{'title_'.$locale} ?? $slide->title_en }}
                                    </h1>
                                    <p class="text-xl md:text-2xl text-gray-200 font-medium max-w-2xl mx-auto drop-shadow-lg">
                                        {{ $slide->{'subtitle_'.$locale} ?? $slide->subtitle_en }}
                                    </p>
                                    @if($slide->cta_url)
                                        <div class="pt-8">
                                            <a href="{{ $slide->cta_url }}" class="inline-block px-12 py-5 bg-white text-gray-900 rounded-full text-base font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all transform hover:scale-110 shadow-2xl">
                                                {{ $slide->{'cta_text_'.$locale} ?? $slide->cta_text ?? 'Learn More' }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{-- Custom Indicators --}}
                <div class="absolute bottom-12 left-1/2 -translate-x-1/2 z-20 flex gap-4">
                    @foreach($heroSlides as $index => $s)
                        <button class="w-12 h-1.5 rounded-full bg-white/20 transition-all duration-500 hover:bg-white/50" data-hero-index="{{ $index }}"></button>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 2. Zone at a Glance Strip (Automated Stats) --}}
    <section class="relative z-20 -mt-16 max-w-7xl mx-auto px-4">
        <div class="bg-gray-900 rounded-[3rem] p-10 md:p-14 shadow-2xl overflow-hidden relative">
            <div class="absolute top-0 right-0 p-20 opacity-10">
                <svg class="w-64 h-64 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-12 relative z-10">
                @php
                    $stats = [
                        ['label' => 'Total Residents', 'value' => number_format($stats['total_population']), 'icon' => '👥', 'text' => 'People'],
                        ['label' => 'Zone Territory', 'value' => number_format($stats['total_area']), 'icon' => '🗺️', 'text' => 'KM²'],
                        ['label' => 'Administration Units', 'value' => $stats['woreda_count'], 'icon' => '🏛️', 'text' => 'Woredas'],
                        ['label' => 'Established Since', 'value' => $stats['earliest_year'], 'icon' => '📜', 'text' => 'Heritage'],
                    ];
                @endphp
                @foreach($stats as $stat)
                    <div class="text-center group">
                        <div class="text-4xl mb-4 transform group-hover:scale-125 transition duration-500">{{ $stat['icon'] }}</div>
                        <div class="text-4xl font-black text-white tracking-tighter">{{ $stat['value'] }}</div>
                        <div class="text-[10px] font-black text-blue-400 uppercase tracking-widest mt-2">{{ $stat['label'] }}</div>
                        <div class="text-xs text-gray-500 mt-1 font-bold">{{ $stat['text'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 3. Historical Background --}}
    @php $history = $sections->where('type', 'history')->first(); @endphp
    @if($history)
    <section class="max-w-7xl mx-auto px-4 py-32 grid grid-cols-1 lg:grid-cols-2 gap-24 items-center">
        <div class="relative group">
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-60"></div>
            <div class="relative rounded-[4rem] overflow-hidden shadow-2xl h-[600px]">
                <img src="{{ asset($history->image_url ?? 'images/placeholders/history.jpg') }}" class="w-full h-full object-cover transition duration-1000 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-blue-900/60 to-transparent"></div>
            </div>
            <div class="absolute -bottom-10 -right-10 bg-white p-10 rounded-[3rem] shadow-2xl max-w-xs border border-gray-100">
                <span class="text-6xl font-black text-blue-600 tracking-tighter block mb-2">{{ $stats['earliest_year'] }}</span>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest leading-tight">Foundation Year of our first Administrative Unit</p>
            </div>
        </div>
        <div class="space-y-8">
            <span class="px-5 py-2 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest">Zone Heritage</span>
            <h2 class="text-5xl md:text-6xl font-black text-gray-900 tracking-tighter uppercase leading-none">
                {{ $history->{'title_'.$locale} ?? $history->title_en }}
            </h2>
            <div class="text-xl text-gray-500 leading-relaxed font-medium">
                {!! nl2br(e($history->{'content_'.$locale} ?? $history->content_en)) !!}
            </div>
        </div>
    </section>
    @endif

    {{-- 4. Mission & Vision --}}
    <section class="bg-gray-50 py-32 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/2 h-full opacity-5 pointer-events-none">
            <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100"><path d="M0 100 Q 50 0 100 100 L 100 0 L 0 0 Z"/></svg>
        </div>
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                @php 
                    $mv = [
                        'mission' => $sections->where('type', 'mission')->first(),
                        'vision' => $sections->where('type', 'vision')->first()
                    ];
                @endphp
                @foreach($mv as $type => $item)
                    @if($item)
                    <div class="bg-white p-16 rounded-[4rem] shadow-xl border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 relative group">
                        <div class="absolute top-10 right-10 text-8xl font-black text-blue-50/50 uppercase tracking-tighter group-hover:text-blue-100/50 transition">{{ $type }}</div>
                        <span class="w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-3xl mb-8 shadow-xl shadow-blue-200">
                            {{ $type === 'mission' ? '🎯' : '👁️' }}
                        </span>
                        <h3 class="text-4xl font-black text-gray-900 tracking-tighter uppercase mb-6 relative z-10">{{ $item->{'title_'.$locale} ?? $item->title_en }}</h3>
                        <p class="text-lg text-gray-500 font-medium leading-relaxed relative z-10">
                            {{ $item->{'content_'.$locale} ?? $item->content_en }}
                        </p>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    {{-- 5. Strategic Objectives (Grid View) --}}
    @php $objectives = $sections->whereIn('type', ['objective', 'general'])->sortBy('sort_order'); @endphp
    @if($objectives->count() > 0)
    <section class="py-32 max-w-7xl mx-auto px-4">
        <div class="text-center mb-20 space-y-4">
            <span class="text-blue-600 font-black uppercase tracking-widest text-[10px]">Strategic Roadmap</span>
            <h2 class="text-5xl font-black text-gray-900 tracking-tighter uppercase">Our Strategic Objectives</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($objectives as $index => $obj)
                <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm hover:shadow-2xl hover:border-blue-100 transition-all duration-500 group relative">
                    <span class="absolute top-8 right-8 text-6xl font-black text-gray-50 group-hover:text-blue-50 transition">0{{ $loop->iteration }}</span>
                    <div class="relative z-10 space-y-6">
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

    {{-- 6. Leadership Organogram --}}
    @if($leadership->count() > 0)
    <section class="bg-gray-900 py-32 rounded-[5rem] mx-4 my-10 overflow-hidden relative">
        <div class="absolute inset-0 bg-[url('/images/pattern.png')] opacity-10"></div>
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center mb-24">
                <span class="text-blue-400 font-black uppercase tracking-widest text-[10px]">Administrative Hierarchy</span>
                <h2 class="text-5xl font-black text-white tracking-tighter uppercase mt-4">Leadership Council</h2>
            </div>
            
            {{-- Visual Hierarchy Structure --}}
            <div class="space-y-32">
                {{-- Level 1: Central Head --}}
                <div class="flex justify-center">
                    @foreach($leadership->where('hierarchy_level', 1) as $head)
                        <div class="relative" x-data="{ open: false }">
                            <div @mouseenter="open = true" @mouseleave="open = false" 
                                class="w-80 group cursor-pointer transition-all duration-500 hover:-translate-y-4">
                                <div class="bg-white rounded-[3.5rem] p-8 text-center shadow-2xl relative overflow-hidden h-[450px] flex flex-col items-center">
                                    <div class="w-48 h-48 rounded-[2.5rem] overflow-hidden mb-6 border-8 border-gray-50 shadow-xl group-hover:scale-110 transition duration-700">
                                        <img src="{{ asset($head->photo_url ?? 'images/placeholders/avatar.jpg') }}" class="w-full h-full object-cover">
                                    </div>
                                    <h3 class="text-2xl font-black text-gray-900 leading-none mb-2">{{ $head->{'name_'.$locale} ?? $head->name_en }}</h3>
                                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-4">{{ $head->{'position_'.$locale} ?? $head->position_en }}</p>
                                    <div class="text-xs text-gray-400 font-medium line-clamp-3 px-4">
                                        {{ $head->{'bio_'.$locale} ?? $head->bio_en }}
                                    </div>

                                    {{-- Hover Overlay with Contacts --}}
                                    <div x-show="open" x-transition.opacity class="absolute inset-0 bg-blue-600/95 backdrop-blur flex flex-col items-center justify-center p-8 text-white">
                                        <div class="space-y-6 text-center">
                                            <p class="text-[10px] font-black uppercase tracking-[0.3em]">Contact Channels</p>
                                            <div class="space-y-4">
                                                <div class="flex items-center gap-3 justify-center">
                                                    <span class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">📞</span>
                                                    <span class="font-bold">{{ $head->phone ?? 'Private' }}</span>
                                                </div>
                                                <div class="flex items-center gap-3 justify-center">
                                                    <span class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">✉️</span>
                                                    <span class="font-bold text-sm">{{ $head->email ?? 'admin@osz.gov.et' }}</span>
                                                </div>
                                                @if($head->office_location_en)
                                                <div class="flex items-center gap-3 justify-center">
                                                    <span class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">🏢</span>
                                                    <span class="font-bold text-[10px] uppercase">{{ $head->{'office_location_'.$locale} ?? $head->office_location_en }}</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Connector --}}
                                <div class="w-1 h-32 bg-gradient-to-b from-white to-blue-600/50 mx-auto mt-4 rounded-full"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Level 2: Vices --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 max-w-6xl mx-auto px-4">
                    @php $level2 = $leadership->where('hierarchy_level', 2); @endphp
                    @foreach($level2 as $vice)
                        <div class="relative group" x-data="{ open: false }">
                            <div @mouseenter="open = true" @mouseleave="open = false" 
                                class="bg-gray-800 rounded-[3rem] p-8 text-center border-2 border-gray-700 hover:border-blue-500/50 transition-all duration-500 h-[400px] flex flex-col items-center group relative overflow-hidden">
                                <div class="w-32 h-32 rounded-3xl overflow-hidden mb-6 border-4 border-gray-700 shadow-xl group-hover:scale-105 transition duration-500">
                                    <img src="{{ asset($vice->photo_url ?? 'images/placeholders/avatar.jpg') }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-500">
                                </div>
                                <h3 class="text-xl font-black text-white leading-none mb-2">{{ $vice->{'name_'.$locale} ?? $vice->name_en }}</h3>
                                <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-4">{{ $vice->{'position_'.$locale} ?? $vice->position_en }}</p>
                                <p class="text-xs text-gray-500 font-medium line-clamp-2 px-4">{{ $vice->{'bio_'.$locale} ?? $vice->bio_en }}</p>

                                {{-- Contact Overlay --}}
                                <div x-show="open" x-transition.opacity class="absolute inset-0 bg-blue-600/90 backdrop-blur flex flex-col items-center justify-center p-6 text-white text-center">
                                    <div class="space-y-4">
                                        <p class="text-xs font-bold">{{ $vice->email }}</p>
                                        <p class="text-lg font-black tracking-tighter">{{ $vice->phone }}</p>
                                        <div class="pt-4">
                                            <span class="px-4 py-2 bg-white/20 rounded-full text-[8px] font-black uppercase tracking-widest">Connect Now</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Level 3+: Grid --}}
                @php $otherLevels = $leadership->where('hierarchy_level', '>', 2); @endphp
                @if($otherLevels->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                        @foreach($otherLevels as $member)
                            <div class="bg-gray-800/30 backdrop-blur-sm rounded-[2rem] p-6 text-center border border-gray-700/50 hover:bg-gray-800 transition duration-300 group cursor-help relative" x-data="{ open: false }">
                                <div @mouseenter="open = true" @mouseleave="open = false" >
                                    <div class="w-16 h-16 rounded-2xl overflow-hidden mx-auto mb-4 border-2 border-gray-700">
                                        <img src="{{ asset($member->photo_url ?? 'images/placeholders/avatar.jpg') }}" class="w-full h-full object-cover">
                                    </div>
                                    <h4 class="text-sm font-black text-white leading-tight uppercase group-hover:text-blue-400 transition">{{ $member->{'name_'.$locale} ?? $member->name_en }}</h4>
                                    <p class="text-[8px] font-bold text-gray-500 uppercase mt-1">{{ $member->{'position_'.$locale} ?? $member->position_en }}</p>
                                </div>

                                {{-- Mini Contact Tooltip --}}
                                <div x-show="open" x-transition class="absolute bottom-full left-1/2 -translate-x-1/2 mb-4 w-48 bg-white rounded-2xl p-4 shadow-2xl z-50 text-gray-900 border border-gray-100">
                                    <div class="text-[8px] font-black text-blue-600 uppercase mb-2 tracking-widest">Official Contact</div>
                                    <div class="text-xs font-bold text-gray-800 mb-1">{{ $member->email ?? 'Contact in Dept.' }}</div>
                                    <div class="text-xs font-black text-gray-400">{{ $member->phone }}</div>
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -translate-y-px w-3 h-3 bg-white rotate-45 border-r border-b border-gray-100"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.mainHeroSwiper', {
            loop: true,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            on: {
                slideChangeTransitionStart: function() {
                    const index = this.realIndex;
                    document.querySelectorAll('[data-hero-index]').forEach(dot => {
                        dot.classList.toggle('w-12', parseInt(dot.dataset.heroIndex) === index);
                        dot.classList.toggle('bg-white', parseInt(dot.dataset.heroIndex) === index);
                        dot.classList.toggle('w-4', parseInt(dot.dataset.heroIndex) !== index);
                        dot.classList.toggle('bg-white/20', parseInt(dot.dataset.heroIndex) !== index);
                    });
                }
            }
        });

        document.querySelectorAll('[data-hero-index]').forEach(dot => {
            dot.onclick = () => swiper.slideToLoop(parseInt(dot.dataset.heroIndex));
        });
    });
</script>

<style>
    @keyframes fade-up {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up { animation: fade-up 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    [x-cloak] { display: none !important; }
</style>
@endsection