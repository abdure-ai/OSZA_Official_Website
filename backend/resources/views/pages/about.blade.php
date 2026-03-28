@extends('layouts.app')
@section('title', __('about') . ' — OSZA')
@section('content')
    @php $locale = session('locale', 'en'); @endphp

    {{-- ═══════════════════════════════════════════ ABOUT HERO ══ --}}
    <section class="relative bg-blue-900 text-white py-24 md:py-36 overflow-hidden">
        {{-- Decorative Background --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/40 z-10"></div>
            <div class="absolute inset-0 bg-black/20 z-10"></div>
            <img src="https://images.unsplash.com/photo-1523050335456-1ccfa5277cc2?q=80&w=1920&auto=format&fit=crop" 
                 class="w-full h-full object-cover scale-105" alt="OSZA Office">
        </div>

        <div class="max-w-[1440px] mx-auto px-4 relative z-20">
            <div class="max-w-3xl">
                <span class="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                    Our Foundation
                </span>
                <h1 class="text-5xl md:text-8xl font-black mb-6 leading-none antialiased drop-shadow-2xl">
                    Dedicated to<br>
                    <span class="text-[#f5a623]">Propelling Progress.</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-100 font-medium leading-relaxed drop-shadow-lg opacity-90 max-w-2xl">
                    The Oromo Special Zone Administration is built on a legacy of service, transparency, and a relentless commitment to the prosperity of our people.
                </p>
            </div>
        </div>
        {{-- Bottom fade --}}
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    <div class="max-w-[1440px] mx-auto px-4 py-12 space-y-14">
        {{-- Mission & Vision --}}
        {{-- Mission & Vision --}}
        <section class="max-w-5xl mx-auto space-y-12">
            <div class="grid md:grid-cols-2 gap-10">
                <div class="group bg-white rounded-3xl p-10 shadow-sm border border-gray-100 hover:shadow-2xl hover:shadow-[#1a56db]/5 transition-all duration-500 transform hover:-translate-y-2 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-2 h-full bg-[#1a56db]"></div>
                    <div class="w-16 h-16 bg-[#1a56db]/5 rounded-2xl flex items-center justify-center text-[#1a56db] mb-8 group-hover:bg-[#1a56db] group-hover:text-white transition-colors duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h2 class="text-3xl font-black text-gray-900 mb-4 uppercase tracking-tighter">Our Mission</h2>
                    <p class="text-gray-500 leading-relaxed font-medium">
                        To deliver transparent, accountable, and efficient public administration that improves the quality of life for all citizens of the Oromo Special Zone through inclusive development, good governance, and community engagement.
                    </p>
                </div>

                <div class="group bg-white rounded-3xl p-10 shadow-sm border border-gray-100 hover:shadow-2xl hover:shadow-[#f5a623]/5 transition-all duration-500 transform hover:-translate-y-2 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-2 h-full bg-[#f5a623]"></div>
                    <div class="w-16 h-16 bg-[#f5a623]/5 rounded-2xl flex items-center justify-center text-[#f5a623] mb-8 group-hover:bg-[#f5a623] group-hover:text-white transition-colors duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h2 class="text-3xl font-black text-gray-900 mb-4 uppercase tracking-tighter">Our Vision</h2>
                    <p class="text-gray-500 leading-relaxed font-medium">
                        A prosperous, peaceful and sustainable Oromo Special Zone where every citizen enjoys equal rights and opportunities for social, economic and cultural development.
                    </p>
                </div>
            </div>
        </section>

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