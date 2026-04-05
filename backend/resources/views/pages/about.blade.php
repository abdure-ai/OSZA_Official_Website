@extends('layouts.app')

@php
    $locale = session('locale', 'en');
@endphp

@section('title', 'About Oromo Special Zone')

@section('content')
<div class="bg-white">
    {{-- Hero Slider --}}
    @if($heroSlides->count() > 0)
        <section class="relative h-[60vh] md:h-[80vh] w-full overflow-hidden">
            <div class="swiper heroSwiper h-full w-full">
                <div class="swiper-wrapper">
                    @foreach($heroSlides as $slide)
                        <div class="swiper-slide relative h-full w-full">
                            @if($slide->media_type === 'video')
                                <video src="{{ asset($slide->media_url) }}" class="absolute inset-0 w-full h-full object-cover" autoplay muted loop playsinline></video>
                            @else
                                <img src="{{ asset($slide->media_url) }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $slide->{'title_'.$locale} }}">
                            @endif
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center text-center p-6">
                                <div class="max-w-4xl">
                                    <h1 class="text-4xl md:text-7xl font-black text-white uppercase tracking-tighter mb-4 animate-fade-in">
                                        {{ $slide->{'title_'.$locale} ?? $slide->title_en }}
                                    </h1>
                                    <p class="text-lg md:text-xl text-gray-200 font-medium max-w-2xl mx-auto">
                                        {{ $slide->{'subtitle_'.$locale} ?? $slide->subtitle_en }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </section>
    @endif

    {{-- Zone Stats Strip --}}
    <section class="relative z-10 -mt-12 md:-mt-20 max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
            <div class="bg-white rounded-[2rem] p-8 shadow-2xl border border-gray-100 flex flex-col items-center text-center group hover:bg-blue-900 transition-colors duration-500">
                <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-2 group-hover:text-blue-200 transition-colors">Total Residents</span>
                <span class="text-3xl md:text-4xl font-black text-gray-900 group-hover:text-white transition-colors tracking-tighter">
                    {{ number_format($stats['total_population'] ?? 0) }}
                </span>
            </div>
            <div class="bg-white rounded-[2rem] p-8 shadow-2xl border border-gray-100 flex flex-col items-center text-center group hover:bg-blue-900 transition-colors duration-500">
                <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-2 group-hover:text-blue-200 transition-colors">Total Area</span>
                <span class="text-3xl md:text-4xl font-black text-gray-900 group-hover:text-white transition-colors tracking-tighter">
                    {{ number_format($stats['total_area'] ?? 0) }} <small class="text-xs">KM²</small>
                </span>
            </div>
            <div class="bg-white rounded-[2rem] p-8 shadow-2xl border border-gray-100 flex flex-col items-center text-center group hover:bg-blue-900 transition-colors duration-500">
                <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-2 group-hover:text-blue-200 transition-colors">Woredas</span>
                <span class="text-3xl md:text-4xl font-black text-gray-900 group-hover:text-white transition-colors tracking-tighter">
                    {{ $stats['woreda_count'] ?? 0 }}
                </span>
            </div>
            <div class="bg-white rounded-[2rem] p-8 shadow-2xl border border-gray-100 flex flex-col items-center text-center group hover:bg-blue-900 transition-colors duration-500">
                <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-2 group-hover:text-blue-200 transition-colors">Legacy Since</span>
                <span class="text-3xl md:text-4xl font-black text-gray-900 group-hover:text-white transition-colors tracking-tighter">
                    {{ $stats['earliest_year'] ?? 'N/A' }}
                </span>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-20 space-y-32">
        
        {{-- Historical Background --}}
        @php $history = $sections->where('type', 'history')->first(); @endphp
        @if($history)
            <section class="grid md:grid-cols-2 gap-16 items-center">
                <div class="relative order-2 md:order-1">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-60"></div>
                    <span class="text-[#1a56db] font-black uppercase tracking-widest text-xs mb-4 block">Zone Heritage</span>
                    <h2 class="text-5xl md:text-6xl font-black text-gray-900 mb-8 uppercase tracking-tighter leading-none">
                        {{ $history->{'title_' . $locale} ?? $history->title_en }}
                    </h2>
                    <div class="text-xl text-gray-500 font-medium leading-relaxed">
                        {!! nl2br(e($history->{'content_' . $locale} ?? $history->content_en)) !!}
                    </div>
                </div>
                <div class="order-1 md:order-2 rounded-[3.5rem] overflow-hidden shadow-2xl border-8 border-white transform md:rotate-3 hover:rotate-0 transition-transform duration-700">
                    <img src="{{ $history->image_url ? asset($history->image_url) : asset('images/about-history.jpg') }}" class="w-full h-[600px] object-cover" alt="History">
                </div>
            </section>
        @endif

        {{-- Mission & Vision Row --}}
        <section class="grid md:grid-cols-2 gap-10">
            @php 
                $mission = $sections->where('type', 'mission')->first();
                $vision = $sections->where('type', 'vision')->first();
            @endphp
            
            @if($mission)
            <div class="group bg-white rounded-[3rem] p-16 shadow-sm border border-gray-100 hover:shadow-2xl transition-all duration-500 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-[#1a56db]"></div>
                <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center mb-10 group-hover:scale-110 transition-transform">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h2 class="text-4xl font-black text-gray-900 mb-6 uppercase tracking-tighter">{{ $mission->{'title_' . $locale} ?? $mission->title_en }}</h2>
                <p class="text-gray-500 text-xl leading-relaxed font-medium line-clamp-6">
                    {{ $mission->{'content_' . $locale} ?? $mission->content_en }}
                </p>
            </div>
            @endif

            @if($vision)
            <div class="group bg-white rounded-[3rem] p-16 shadow-sm border border-gray-100 hover:shadow-2xl transition-all duration-500 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-[#f5a623]"></div>
                <div class="w-20 h-20 bg-orange-50 text-orange-600 rounded-3xl flex items-center justify-center mb-10 group-hover:scale-110 transition-transform">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <h2 class="text-4xl font-black text-gray-900 mb-6 uppercase tracking-tighter">{{ $vision->{'title_' . $locale} ?? $vision->title_en }}</h2>
                <p class="text-gray-500 text-xl leading-relaxed font-medium line-clamp-6">
                    {{ $vision->{'content_' . $locale} ?? $vision->content_en }}
                </p>
            </div>
            @endif
        </section>

        {{-- Objectives & Other Sections --}}
        @php $otherSections = $sections->whereNotIn('type', ['hero', 'history', 'mission', 'vision']); @endphp
        @foreach($otherSections as $section)
            <section class="max-w-4xl mx-auto text-center py-20 border-t border-gray-50">
                <h2 class="text-4xl font-black text-gray-900 mb-8 uppercase tracking-tighter">
                    {{ $section->{'title_' . $locale} ?? $section->title_en }}
                </h2>
                <div class="text-gray-500 text-xl leading-relaxed font-medium whitespace-pre-line">
                    {{ $section->{'content_' . $locale} ?? $section->content_en }}
                </div>
            </section>
        @endforeach

        {{-- Simple Leadership Grid --}}
        @if($leadership->isNotEmpty())
            <section class="py-20 border-t border-gray-100">
                <div class="text-center mb-24">
                    <span class="text-[#1a56db] font-black uppercase tracking-widest text-[10px] mb-4 block">Governance</span>
                    <h2 class="text-5xl md:text-6xl font-black text-gray-900 uppercase tracking-tighter">The Leadership Council</h2>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
                    @foreach($leadership as $leader)
                        <div class="group bg-white rounded-[3rem] p-10 flex flex-col items-center text-center hover:shadow-2xl transition-all duration-500 border border-transparent hover:border-gray-100">
                            <div class="relative mb-8">
                                <div class="absolute inset-0 bg-[#1a56db] rounded-full blur-2xl opacity-0 group-hover:opacity-10 transition-opacity"></div>
                                @if($leader->photo_url)
                                    <img src="{{ asset($leader->photo_url) }}"
                                        alt="{{ $leader->{'name_' . $locale} ?? $leader->name_en }}"
                                        class="w-48 h-48 rounded-full object-cover border-4 border-white shadow-2xl relative z-10">
                                @else
                                    <div class="w-48 h-48 rounded-full bg-gray-50 flex items-center justify-center border-4 border-white shadow-2xl relative z-10 text-[#1a56db]">
                                        <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    </div>
                                @endif
                            </div>
                            <h3 class="font-black text-gray-900 text-2xl tracking-tighter leading-tight mb-2 uppercase">{{ $leader->{'name_' . $locale} ?? $leader->name_en }}</h3>
                            <p class="text-[10px] font-black text-[#1a56db] uppercase tracking-[0.25em] px-5 py-2 bg-blue-50 rounded-full mt-4">
                                {{ $leader->{'position_' . $locale} ?? $leader->position_en }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.heroSwiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    });
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 1s ease-out forwards;
    }
</style>
@endsection