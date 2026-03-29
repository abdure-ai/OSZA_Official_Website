@extends('layouts.app')
@section('title', ($vacancy->title_en) . ' — OSZA')
@section('content')
    @php $locale = session('locale', 'en'); @endphp

    <section class="bg-gray-50 min-h-screen pb-20 pt-10">
        <div class="max-w-[1440px] mx-auto px-4">
            {{-- Breadcrumb --}}
            <nav class="flex mb-8 text-xs font-black uppercase tracking-widest text-gray-400">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition">{{ __('home') }}</a>
                <span class="mx-3">/</span>
                <a href="{{ route('vacancies.index') }}" class="hover:text-blue-600 transition">{{ __('join_our_team') }}</a>
                <span class="mx-3">/</span>
                <span class="text-blue-900 font-black">{{ __('job_detail') }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                {{-- Main Content --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[3rem] p-8 md:p-12 shadow-2xl shadow-blue-900/5 border border-gray-100">
                        <div class="flex flex-wrap items-center gap-4 mb-8">
                            <span
                                class="bg-blue-50 text-blue-600 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-sm">
                                {{ $vacancy->department }}
                            </span>
                            <span
                                class="bg-gray-50 text-gray-400 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest">
                                {{ $vacancy->vacancy_type ?: 'Full-time' }}
                            </span>
                        </div>

                        <h1 class="text-3xl md:text-5xl font-black text-gray-900 mb-8 tracking-tight leading-none">
                            {{ $vacancy->{'title_' . $locale} ?? $vacancy->title_en }}
                        </h1>

                        <div class="mb-12">
                            <h2
                                class="text-sm font-black text-blue-900 uppercase tracking-widest mb-6 flex items-center gap-3">
                                <span class="w-8 h-px bg-blue-900"></span> {{ __('role_description') }}
                            </h2>
                            <div class="prose prose-blue max-w-none text-gray-600 font-medium leading-relaxed">
                                {!! nl2br(e($vacancy->{'description_' . $locale} ?? $vacancy->description_en)) !!}
                            </div>
                        </div>

                        @if($vacancy->{'requirements_' . $locale} ?? $vacancy->requirements_en)
                            <div>
                                <h2
                                    class="text-sm font-black text-blue-900 uppercase tracking-widest mb-6 flex items-center gap-3">
                                    <span class="w-8 h-px bg-blue-900"></span> {{ __('candidate_requirements') }}
                                </h2>
                                <div class="prose prose-blue max-w-none text-gray-600 font-medium leading-relaxed">
                                    {!! nl2br(e($vacancy->{'requirements_' . $locale} ?? $vacancy->requirements_en)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1 space-y-8">
                    {{-- Application Card --}}
                    <div class="bg-blue-900 text-white p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                        <h3 class="text-lg font-black mb-8 tracking-tight relative z-10">{{ __('join_the_team') }}</h3>

                        <div class="space-y-6 relative z-10">
                            <div>
                                <span
                                    class="block text-[10px] font-black uppercase tracking-widest opacity-40 mb-1">{{ __('app_deadline') }}</span>
                                <span class="text-lg font-bold text-[#f5a623]">
                                    {{ $vacancy->deadline instanceof \Carbon\Carbon ? $vacancy->deadline->format('F d, Y') : $vacancy->deadline }}
                                </span>
                            </div>

                            <div>
                                <span class="block text-[10px] font-black uppercase tracking-widest opacity-40 mb-1">{{ __('work_location') }}</span>
                                <span class="text-lg font-bold">
                                    {{ $vacancy->{'location_' . $locale} ?? $vacancy->location_en ?? 'Zone-wide' }}
                                </span>
                            </div>

                            @if($vacancy->document_url)
                                <div class="pt-6 border-t border-white/10 space-y-3">
                                    <a href="{{ asset($vacancy->document_url) }}" target="_blank"
                                        class="block w-full text-center bg-white/10 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-white/20 transition-all flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ __('read_online') }}
                                    </a>
                                    <a href="{{ asset($vacancy->document_url) }}" download
                                        class="block w-full text-center bg-[#f5a623] text-blue-900 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:scale-105 transition-all shadow-xl shadow-black/20 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        {{ __('download_pdf') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Future Ops Card --}}
                    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-xl">
                        <h4 class="text-sm font-black text-blue-900 uppercase tracking-widest mb-4">{{ __('talent_database') }}</h4>
                        <p class="text-xs text-gray-500 font-medium leading-relaxed mb-6">
                            {{ __('talent_subtitle') }}
                        </p>
                        <a href="/contact"
                            class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">{{ __('contact_hr') }} →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection