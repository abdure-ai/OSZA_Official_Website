@extends('layouts.app')
@section('title', ($investment->{'title_' . session('locale', 'en')} ?? $investment->title_en) . ' — ' . __('investment') . ' — OSZA')

@section('content')
    @php
        $locale = session('locale', 'en');
        $title = $investment->{'title_' . $locale} ?? $investment->title_en;
        $description = $investment->{'description_' . $locale} ?? $investment->description_en;
        $location = $investment->{'location_' . $locale} ?? $investment->location_en ?? $investment->location;
        $incentives = $investment->{'incentives_' . $locale} ?? $investment->incentives_en;
    @endphp

    {{-- HERO SECTION --}}
    <div class="relative bg-blue-900 py-24 md:py-32 overflow-hidden text-center flex flex-col items-center justify-center">
        @if($investment->thumbnail_url)
            <div class="absolute inset-0 z-0">
                <img src="{{ asset($investment->thumbnail_url) }}" alt="{{ $title }}" class="w-full h-full object-cover opacity-30">
                <div class="absolute inset-0 bg-gradient-to-t from-blue-950 via-blue-900/80 to-blue-900/60"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-r from-blue-950 to-blue-800 z-0"></div>
        @endif

        <div class="relative z-10 max-w-4xl mx-auto px-4">
            <span class="inline-block px-4 py-1 bg-amber-500 text-blue-900 font-black uppercase text-[10px] tracking-[0.2em] rounded-full mb-6 shadow-xl">
                {{ $investment->category ?: __('investment') }}
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight drop-shadow-lg">
                {{ $title }}
            </h1>
            <div class="flex items-center justify-center gap-6 mt-8">
                <div class="flex items-center gap-2 text-blue-100 bg-blue-950/50 px-4 py-2 rounded-xl backdrop-blur-sm border border-blue-800/50">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="text-sm font-bold uppercase tracking-wide">{{ $location ?: 'Oromo Special Zone' }}</span>
                </div>
                <div class="flex items-center gap-2 text-blue-100 bg-blue-950/50 px-4 py-2 rounded-xl backdrop-blur-sm border border-blue-800/50 hidden sm:flex">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="text-sm font-bold uppercase tracking-wide">{{ $investment->sector ?: 'Diversified' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-[1440px] mx-auto px-4 py-16">
        <div class="grid lg:grid-cols-3 gap-12">
            
            {{-- Left Column: Description & Incentives --}}
            <div class="lg:col-span-2 space-y-12">
                <section>
                    <div class="flex items-end gap-4 mb-6">
                        <h2 class="text-3xl font-black text-gray-900 border-l-4 border-[#1a56db] pl-4">{{ __('about') }} {{ __('investment') }}</h2>
                    </div>
                    <div class="prose max-w-none text-gray-600 leading-relaxed font-medium text-lg">
                        {!! nl2br(e($description)) !!}
                    </div>
                </section>

                @if($incentives)
                <section class="bg-amber-50 rounded-[2rem] p-8 md:p-12 border border-amber-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl -z-0"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-amber-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                            </div>
                            <h3 class="text-2xl font-black text-amber-900">{{ __('Incentives') ?? 'Government Incentives' }}</h3>
                        </div>
                        <div class="prose max-w-none text-amber-800 font-medium">
                            {!! nl2br(e($incentives)) !!}
                        </div>
                    </div>
                </section>
                @endif
            </div>

            {{-- Right Column: Sidebar Data --}}
            <div class="space-y-8">
                {{-- Quick Facts --}}
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                    <h4 class="font-black text-gray-900 mb-6 uppercase tracking-widest text-sm text-center">{{ __('Quick Facts') ?? 'Quick Facts' }}</h4>
                    <ul class="space-y-6">
                        <li class="flex items-center justify-between border-b border-gray-50 pb-4">
                            <span class="text-gray-400 font-bold text-xs uppercase">{{ __('Status') ?? 'Status' }}</span>
                            <span class="text-[#1a56db] font-black text-sm uppercase bg-blue-50 px-3 py-1 rounded-full">{{ $investment->status ?: 'Open' }}</span>
                        </li>
                        <li class="flex items-center justify-between border-b border-gray-50 pb-4">
                            <span class="text-gray-400 font-bold text-xs uppercase">{{ __('Sector') ?? 'Sector' }}</span>
                            <span class="text-gray-900 font-bold text-sm text-right max-w-[150px] truncate">{{ $investment->sector ?: 'Diversified' }}</span>
                        </li>
                        <li class="flex items-center justify-between border-b border-gray-50 pb-4">
                            <span class="text-gray-400 font-bold text-xs uppercase">{{ __('Category') ?? 'Category' }}</span>
                            <span class="text-gray-900 font-bold text-sm text-right max-w-[150px] truncate">{{ $investment->category ?: 'General' }}</span>
                        </li>
                        @if($investment->budget)
                        <li class="flex items-center justify-between border-b border-gray-50 pb-4">
                            <span class="text-gray-400 font-bold text-xs uppercase">{{ __('Budget') ?? 'Estimated Budget' }}</span>
                            <span class="text-green-600 font-black text-sm text-right">{{ $investment->budget }}</span>
                        </li>
                        @endif
                    </ul>
                </div>

                {{-- Contact Box --}}
                <div class="bg-gray-900 rounded-3xl p-8 text-white relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#1a56db]/30 rounded-full blur-3xl z-0"></div>
                    <div class="relative z-10">
                        <h4 class="font-black mb-2 text-xl">{{ __('contact_us') }}</h4>
                        <p class="text-gray-400 text-sm mb-6">{{ __('Contact our investment bureau for a consultation or to request more information.') ?? 'Contact us for a consultation.' }}</p>
                        
                        <div class="space-y-4 mb-8">
                            @if($investment->contact_name)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <span class="font-bold text-sm">{{ $investment->contact_name }}</span>
                                </div>
                            @endif
                            @if($investment->contact_phone)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <span class="font-bold text-sm">{{ $investment->contact_phone }}</span>
                                </div>
                            @endif
                            @if($investment->contact_email)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="font-bold text-sm">{{ $investment->contact_email }}</span>
                                </div>
                            @endif
                        </div>

                        <a href="{{ route('contact.index') }}" class="block w-full py-4 text-center bg-[#1a56db] text-white font-black uppercase tracking-widest text-xs rounded-xl hover:bg-blue-600 transition shadow-lg shadow-blue-500/20">{{ __('contact_us') }}</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Investments --}}
        @if($related->isNotEmpty())
            <div class="mt-20 pt-16 border-t border-gray-100">
                <div class="flex items-end gap-4 mb-10">
                    <h2 class="text-3xl font-black text-gray-900 border-l-4 border-[#1a56db] pl-4">Similar Opportunities</h2>
                </div>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($related as $inv)
                        <a href="{{ route('investment.show', $inv->id) }}" class="group block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-xl hover:border-blue-200 transition-all duration-300">
                            <div class="relative h-48 overflow-hidden">
                                @if($inv->thumbnail_url)
                                    <img src="{{ asset($inv->thumbnail_url) }}" alt="{{ $inv->title_en }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <div class="w-full h-full bg-blue-50 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                @endif
                                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur rounded px-2 py-1 text-[10px] font-bold text-blue-700 uppercase">
                                    {{ $inv->sector ?: 'Diversified' }}
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-gray-900 mb-2 truncate group-hover:text-[#1a56db] transition-colors">{{ $inv->{'title_' . $locale} ?? $inv->title_en }}</h3>
                                <p class="text-gray-500 text-xs line-clamp-2">{{ $inv->{'description_' . $locale} ?? $inv->description_en }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
