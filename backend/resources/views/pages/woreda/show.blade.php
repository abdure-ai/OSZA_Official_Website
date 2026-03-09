@extends('layouts.app')
@section('title', ($woreda->{'name_' . session('locale', 'en')} ?? $woreda->name_en) . ' Woreda — OSZA')
@section('content')
    @php $locale = session('locale', 'en');
    $slug = $woreda->slug; 
    $stats = [
        ['label' => __('population'), 'value' => $woreda->population, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
        ['label' => __('area'), 'value' => $woreda->area_km2, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>'],
        ['label' => __('est'), 'value' => $woreda->established_year, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
        ['label' => __('capital'), 'value' => $woreda->{'capital_' . $locale} ?? $woreda->capital_en, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>'],
    ]; @endphp

    @include('pages.woreda.partials.header')
    @include('pages.woreda.partials.tabs')

    {{-- Content --}}
    <div class="max-w-[1440px] mx-auto px-4 py-10 space-y-10">

        {{-- Stats Dashboard --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 -mt-20 relative z-30">
            @foreach($stats as $stat)
                <div class="group bg-white rounded-3xl border border-gray-100 shadow-xl p-8 transition-all duration-500 hover:-translate-y-2">
                    <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#f5a623] group-hover:text-white transition-all duration-300 text-gray-400 group-hover:rotate-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">{!! $stat['icon'] !!}</svg>
                    </div>
                    <p class="text-2xl font-black text-gray-900 mb-1 tracking-tighter">{{ $stat['value'] ?: '—' }}</p>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest group-hover:text-[#1a56db] transition-colors">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-6">
                {{-- Description --}}
                @if($woreda->{'description_' . $locale} ?? $woreda->description_en)
                    <section class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-10">
                        <h3 class="text-2xl font-black text-gray-900 mb-6 uppercase tracking-tighter">{{ __('about_woreda') }}</h3>
                        <div class="h-1.5 w-16 bg-[#1a56db] rounded-full mb-8"></div>
                        <p class="text-gray-600 leading-relaxed font-medium text-lg antialiased">
                            {{ $woreda->{'description_' . $locale} ?? $woreda->description_en }}
                        </p>
                        <a href="{{ route('woreda.about', $slug) }}"
                            class="inline-flex items-center mt-8 text-[#1a56db] font-black uppercase tracking-widest text-xs hover:gap-3 transition-all">{{ __('learn_more') }} <span class="ml-2">→</span></a>
                    </section>
                @endif

                {{-- Quick Links --}}
                <section>
                    <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('quick_access') }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <a href="{{ route('woreda.about', $slug) }}"
                            class="flex flex-col items-center text-center p-5 rounded-xl border border-blue-100 bg-blue-50 text-blue-700 hover:shadow-md transition">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-semibold text-sm">{{ __('about_woreda') }}</span>
                        </a>
                        <a href="{{ route('woreda.services', $slug) }}"
                            class="flex flex-col items-center text-center p-5 rounded-xl border border-green-100 bg-green-50 text-green-700 hover:shadow-md transition">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="font-semibold text-sm">{{ __('services_offices') }}</span>
                        </a>
                        <a href="{{ route('woreda.contact', $slug) }}"
                            class="flex flex-col items-center text-center p-5 rounded-xl border border-amber-100 bg-amber-50 text-amber-700 hover:shadow-md transition">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span class="font-semibold text-sm">{{ __('contact_directory') }}</span>
                        </a>
                    </div>
                </section>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">
                @if($woreda->administrator_name)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6 text-center">
                        @if($woreda->administrator_photo_url)
                            <img src="{{ config('app.url') . $woreda->administrator_photo_url }}"
                                alt="{{ $woreda->administrator_name }}"
                                class="w-28 h-36 rounded-2xl object-cover border-4 border-white shadow-lg mx-auto mb-4">
                        @else
                            <div
                                class="w-28 h-36 rounded-2xl bg-green-50 flex items-center justify-center mx-auto mb-4 border-4 border-dashed border-green-200">
                                <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        @endif
                        <h4 class="text-lg font-extrabold text-gray-900 mb-0.5">{{ $woreda->administrator_name }}</h4>
                        <p class="text-xs font-bold text-green-700 uppercase tracking-widest mb-3">
                            {{ $woreda->{'administrator_title_' . $locale} ?? $woreda->administrator_title ?? 'Woreda Administrator' }}</p>
                        <p class="text-gray-500 italic text-xs leading-relaxed">"Committed to serving the people of
                            {{ $woreda->{'name_' . $locale} ?? $woreda->name_en }} with transparency and dedication."</p>
                    </div>
                @endif

                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-8 space-y-5">
                    <h4 class="font-black text-gray-900 text-sm uppercase tracking-[0.2em] mb-4 border-b pb-4 border-dashed border-gray-100">{{ __('direct_contact') }}</h4>
                    @if($woreda->contact_phone)
                        <div class="flex items-center gap-4 text-sm text-gray-600 font-bold group">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-[#1a56db] group-hover:bg-[#1a56db] group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                </svg>
                            </div>
                            <a href="tel:{{ $woreda->contact_phone }}" class="hover:text-[#1a56db] transition-colors">{{ $woreda->contact_phone }}</a>
                        </div>
                    @endif
                    @if($woreda->contact_email)
                        <div class="flex items-center gap-4 text-sm text-gray-600 font-bold group">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-[#1a56db] group-hover:bg-[#1a56db] group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <a href="mailto:{{ $woreda->contact_email }}"
                                class="hover:text-[#1a56db] transition-colors truncate">{{ $woreda->contact_email }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Gallery Preview --}}
        @if($recentPhotos->isNotEmpty())
            <section>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ __('photo_gallery') }}
                    </h3>
                    <a href="{{ route('woreda.gallery', $slug) }}"
                        class="text-sm text-green-700 font-medium hover:underline">{{ __('view_all') }} →</a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($recentPhotos as $photo)
                        <div class="relative h-36 rounded-xl overflow-hidden group">
                            <img src="{{ config('app.url') . $photo->image_url }}" alt="{{ $photo->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-2">
                                <p class="text-white text-xs font-medium truncate">{{ $photo->title }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection