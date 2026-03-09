@extends('layouts.app')
@section('title', 'About ' . ($woreda->name_en) . ' — OSZA')
@section('content')
    @php $locale = session('locale', 'en'); $slug = $woreda->slug; @endphp
    @include('pages.woreda.partials.header')
    @include('pages.woreda.partials.tabs')

    <div class="max-w-[1440px] mx-auto px-4 py-10">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-8">
                <section class="prose prose-sm max-w-none text-gray-600">
                    <h2 class="text-2xl font-bold text-gray-900 border-b pb-2 mb-4">{{ __('history_geography') }}</h2>
                    <p>{{ $woreda->{'description_' . $locale} ?? $woreda->description_en }}</p>
                    @if($woreda->{'about_content_' . $locale} ?? $woreda->about_content_en)
                        <div class="mt-4">
                            {!! nl2br(e($woreda->{'about_content_' . $locale} ?? $woreda->about_content_en)) !!}
                        </div>
                    @endif
                </section>

                <div class="grid sm:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition-all hover:shadow-md">
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <div class="p-2 rounded-lg bg-blue-50">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            {{ __('mission') }}
                        </h3>
                        <p class="text-sm text-gray-500 leading-relaxed italic">"{{ $woreda->{'mission_' . $locale} ?? $woreda->mission_en ?? 'To provide quality services and foster sustainable development within the community.' }}"</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition-all hover:shadow-md">
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <div class="p-2 rounded-lg bg-green-50">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </div>
                            {{ __('vision') }}
                        </h3>
                        <p class="text-sm text-gray-500 leading-relaxed italic">"{{ $woreda->{'vision_' . $locale} ?? $woreda->vision_en ?? 'To be a model of excellence in local governance and community prosperity.' }}"</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-7 rounded-[2.5rem] border border-gray-100 shadow-xl shadow-blue-900/5 antialiased">
                    <h3 class="font-black text-gray-900 mb-6 uppercase tracking-widest text-xs flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </span>
                        {{ __('woreda_at_a_glance') }}
                    </h3>
                    <div class="space-y-5">
                        <div class="flex justify-between items-center group">
                            <span class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em] group-hover:text-blue-600 transition-colors">{{ __('population') }}</span>
                            <span class="text-sm font-black text-gray-900 bg-gray-50 px-3 py-1 rounded-full group-hover:bg-blue-50 transition-colors">{{ number_format($woreda->population) }}</span>
                        </div>
                        <div class="flex justify-between items-center group">
                            <span class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em] group-hover:text-blue-600 transition-colors">{{ __('area') }}</span>
                            <span class="text-sm font-black text-gray-900 bg-gray-50 px-3 py-1 rounded-full group-hover:bg-blue-50 transition-colors">{{ number_format($woreda->area_km2) }} km²</span>
                        </div>
                        <div class="flex justify-between items-center group">
                            <span class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em] group-hover:text-blue-600 transition-colors">{{ __('capital_city') }}</span>
                            <span class="text-sm font-black text-gray-900 bg-gray-50 px-3 py-1 rounded-full group-hover:bg-blue-50 transition-colors">{{ $woreda->{'capital_' . $locale} ?? $woreda->capital_en }}</span>
                        </div>
                        <div class="flex justify-between items-center group">
                            <span class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em] group-hover:text-blue-600 transition-colors">{{ __('admin_level') }}</span>
                            <span class="text-sm font-black text-gray-900 bg-gray-50 px-3 py-1 rounded-full group-hover:bg-blue-50 transition-colors">{{ __('third_level') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
