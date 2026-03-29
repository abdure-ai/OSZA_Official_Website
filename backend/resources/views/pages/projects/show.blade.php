@extends('layouts.app')

@section('title', ($project->{'title_' . session('locale', 'en')} ?? $project->title_en) . ' — Project Details')

@section('content')
    @php $locale = session('locale', 'en'); @endphp

    {{-- ═══════════════════════════════════════════ PROJECT HERO ══ --}}
    <section class="relative h-[60vh] md:h-[75vh] overflow-hidden flex items-end pb-20">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent z-10"></div>
            @if($project->cover_image_url)
                <img src="{{ asset($project->cover_image_url) }}" 
                     class="w-full h-full object-cover scale-105" alt="{{ $project->title_en }}">
            @else
                <div class="w-full h-full bg-indigo-900 flex items-center justify-center">
                    <svg class="w-32 h-32 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            @endif
        </div>

        <div class="max-w-[1440px] mx-auto px-4 relative z-20 w-full text-white">
            <nav class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-300 mb-6">
                <a href="{{ route('home') }}" class="hover:text-[#f5a623] transition">{{ __('home') ?? 'Home' }}</a>
                <span>/</span>
                <a href="{{ route('projects.index') }}" class="hover:text-[#f5a623] transition">{{ __('projects') }}</a>
                <span>/</span>
                <span class="text-[#f5a623]">{{ __('details') ?? 'Details' }}</span>
            </nav>
            <h1 class="text-4xl md:text-7xl font-bold mb-4 leading-tight antialiased drop-shadow-xl">
                {{ $project->{'title_' . $locale} ?? $project->title_en }}
            </h1>
            <div class="flex flex-wrap items-center gap-6">
                <div class="flex items-center gap-2 text-[#f5a623] font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>{{ $project->{'location_' . $locale} ?? $project->location_en ?? 'Oromo Special Zone' }}</span>
                </div>
                <div class="w-px h-6 bg-white/20 hidden md:block"></div>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-black uppercase tracking-widest border border-white/30 px-4 py-1.5 rounded-full backdrop-blur-md bg-white/5">
                        {{ __('status') ?? 'Status' }}: {{ $project->status }}
                    </span>
                    <span class="text-sm font-black uppercase tracking-widest border border-white/30 px-4 py-1.5 rounded-full backdrop-blur-md bg-white/5 text-[#f5a623]">
                        {{ __('progress') ?? 'Progress' }}: {{ $project->progress }}%
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ PROJECT CONTENT ══ --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-[1440px] mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
                
                {{-- Main Info --}}
                <div class="lg:col-span-2 space-y-10">
                    <div class="bg-white rounded-[2.5rem] p-10 md:p-16 shadow-2xl shadow-gray-200/50 border border-gray-100 relative overflow-hidden">
                        {{-- Decorative background text --}}
                        <div class="absolute -top-10 -right-10 text-9xl font-black text-gray-50 select-none pointer-events-none uppercase tracking-tighter opacity-50">
                            Info
                        </div>

                        <div class="relative z-10">
                            <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-8 leading-tight tracking-tighter border-l-8 border-[#f5a623] pl-6">
                                {{ __('Overview') ?? 'Overview' }}
                            </h2>
                            <div class="prose prose-xl text-gray-600 font-medium leading-relaxed max-w-none antialiased">
                                {!! nl2br(e($project->{'description_' . $locale} ?? $project->description_en)) !!}
                            </div>
                        </div>
                    </div>

                    {{-- Timeline/Details Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-indigo-900 rounded-[2rem] p-8 text-white shadow-xl shadow-indigo-900/20 group hover:scale-[1.02] transition">
                            <h4 class="text-sm font-black uppercase tracking-[0.2em] text-indigo-400 mb-4">{{ __('Financials') ?? 'Financials' }}</h4>
                            <div class="text-3xl font-black text-[#f5a623] mb-2 tracking-tight">
                                @if($project->budget)
                                    {{ number_format($project->budget) }} ETB
                                @else
                                    {{ __('Allocated') ?? 'Allocated' }}
                                @endif
                            </div>
                            <p class="text-indigo-200 text-sm font-bold uppercase tracking-widest">{{ __('Estimated Budget') ?? 'Estimated Budget' }}</p>
                        </div>

                        <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-xl shadow-gray-200/50 group hover:scale-[1.02] transition">
                            <h4 class="text-sm font-black uppercase tracking-[0.2em] text-gray-400 mb-4">{{ __('Contractor') ?? 'Contractor' }}</h4>
                            <div class="text-2xl font-black text-gray-900 mb-2 tracking-tight">
                                {{ $project->{'contractor_' . $locale} ?? $project->contractor ?? 'Internal OSZA Services' }}
                            </div>
                            <p class="text-gray-400 text-sm font-bold uppercase tracking-widest">{{ __('Executing Entity') ?? 'Executing Entity' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <aside class="space-y-8 sticky top-32">
                    {{-- Status Card --}}
                    <div class="bg-white rounded-[2rem] overflow-hidden shadow-2xl shadow-gray-200/50 border border-gray-100">
                        <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">{{ __('Project Vitality') ?? 'Project Vitality' }}</span>
                            <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
                        </div>
                        <div class="p-8 space-y-8">
                            <div>
                                <div class="flex justify-between items-end mb-3">
                                    <span class="text-3xl font-black text-gray-900 tracking-tighter">{{ $project->progress }}%</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-[#f5a623] mb-1">{{ __('Execution Progress') ?? 'Execution Progress' }}</span>
                                </div>
                                <div class="w-full h-4 bg-gray-100 rounded-full overflow-hidden p-1 shadow-inner">
                                    <div class="h-full bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full shadow-lg transition-all duration-1000" style="width: {{ $project->progress }}%"></div>
                                </div>
                            </div>

                            <div class="space-y-6 pt-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center text-[#1a56db] font-black shadow-sm">
                                        📅
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-bold text-gray-300 uppercase tracking-widest mb-0.5">{{ __('Start Date') ?? 'Start Date' }}</div>
                                        <div class="text-sm font-black text-gray-800">{{ $project->start_date ? date('M d, Y', strtotime($project->start_date)) : 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-orange-50 flex items-center justify-center text-[#f5a623] font-black shadow-sm">
                                        🏁
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-bold text-gray-300 uppercase tracking-widest mb-0.5">{{ __('Expected End') ?? 'Expected End' }}</div>
                                        <div class="text-sm font-black text-gray-800">{{ $project->end_date ? date('M d, Y', strtotime($project->end_date)) : 'Ongoing' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Funding Source --}}
                    @if($project->funding_source)
                        <div class="bg-gray-900 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-2xl">
                            <div class="absolute -bottom-10 -right-10 text-8xl font-black text-white/5 disabled select-none">Source</div>
                            <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 mb-6">{{ __('Funding Source') ?? 'Funding Source' }}</h4>
                            <div class="text-xl font-bold text-[#f5a623] leading-tight">
                                {{ $project->{'funding_source_' . $locale} ?? $project->funding_source }}
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ RELATED PROJECTS ══ --}}
    @if($related->isNotEmpty())
        <section class="py-24 bg-white">
            <div class="max-w-[1440px] mx-auto px-4">
                <div class="flex items-center justify-between mb-12">
                    <h3 class="text-3xl md:text-5xl font-bold text-gray-900 tracking-tighter uppercase">{{ __('Other Projects') ?? 'Other Projects' }}</h3>
                    <a href="{{ route('projects.index') }}" class="text-sm font-black uppercase tracking-widest text-[#1a56db] hover:text-[#f5a623] transition flex items-center gap-2">
                        {{ __('View All') ?? 'View All' }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($related as $rel)
                        <a href="{{ route('projects.show', $rel->id) }}" class="group block bg-white rounded-[2rem] border border-gray-100 overflow-hidden shadow-xl hover:-translate-y-2 transition duration-500">
                            <div class="h-64 overflow-hidden relative">
                                <img src="{{ config('app.url') . ($rel->cover_image_url ?: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=800') }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                <div class="absolute bottom-4 left-4">
                                    <span class="px-3 py-1 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg">
                                        {{ $rel->status }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-8">
                                <h4 class="text-xl font-black text-gray-900 group-hover:text-blue-600 transition truncate">{{ $rel->{'title_' . $locale} ?? $rel->title_en }}</h4>
                                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-2">OSZA Development</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
