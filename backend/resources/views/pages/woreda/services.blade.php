@extends('layouts.app')
@section('title', 'Services — ' . ($woreda->name_en) . ' — OSZA')
@section('content')
    @php $locale = session('locale', 'en');
    $slug = $woreda->slug; @endphp
    @include('pages.woreda.partials.header')
    @include('pages.woreda.partials.tabs')

    <div class="max-w-[1440px] mx-auto px-4 py-10">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 font-heading">{{ __('services_offices') }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('services_subtitle') }}</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $colors = ['blue', 'green', 'amber', 'indigo', 'red', 'purple', 'emerald'];
            @endphp
            @forelse($services as $index => $s)
                @php $color = $colors[$index % count($colors)]; @endphp
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col h-full group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-{{ $color }}-50 text-{{ $color }}-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition duration-300">
                            @if($s->icon_svg)
                                {!! $s->icon_svg !!}
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            @endif
                        </div>
                    </div>
                    @php
                        $official_name = $s->pivot->{'official_name_'.$locale} ?: $s->pivot->official_name_en;
                        $official_title = $s->pivot->{'official_title_'.$locale} ?: $s->pivot->official_title_en;
                        $service_name = $s->{'name_'.$locale} ?: $s->name_en;
                        $service_desc = $s->{'description_'.$locale} ?: $s->description_en;
                    @endphp
                    <h3 class="font-bold text-gray-900 mb-2">{{ $service_name }}</h3>
                    <p class="text-xs text-gray-500 leading-relaxed flex-1">{{ $service_desc ?: 'Service details are available at the Woreda office.' }}</p>
                    
                    @if($official_name)
                    <div class="mt-6 pt-4 border-t border-gray-50">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('service_official') }}</p>
                        <div class="flex items-center gap-3 relative">
                            @if($s->pivot->official_photo_url)
                                <img src="{{ $s->pivot->official_photo_url }}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm" alt="{{ $official_name }}">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-100 border-2 border-white shadow-sm flex items-center justify-center text-gray-400 font-bold text-sm">
                                    {{ mb_strtoupper(mb_substr($official_name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $official_name }}</p>
                                <p class="text-[10px] font-bold text-blue-600 tracking-wider">{{ $official_title }}</p>
                            </div>
                        </div>
                        @if($s->pivot->official_phone)
                        <div class="mt-4 text-xs font-semibold text-gray-500 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            {{ $s->pivot->official_phone }}
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-2">{{ __('no_services') }}</p>
                </div>
            @endforelse
        </div>

        <div
            class="mt-12 bg-[#1a56db] rounded-3xl p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-left">
                <h4 class="text-xl font-bold mb-1">{{ __('need_assistance') }}</h4>
                <p class="text-blue-100 text-sm">{{ __('assistance_subtitle') }}</p>
            </div>
            <a href="{{ route('woreda.contact', $slug) }}"
                class="px-8 py-3 bg-white text-[#1a56db] rounded-xl font-extrabold shadow-lg hover:shadow-xl transition flex items-center gap-2">
                {{ __('visit_contact') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </a>
        </div>
    </div>
@endsection