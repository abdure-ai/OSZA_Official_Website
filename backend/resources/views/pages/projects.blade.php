@extends('layouts.app')
@section('title', __('projects') . ' — OSZA')
@section('content')
    @php $locale = session('locale', 'en'); @endphp

    {{-- ═══════════════════════════════════════════ PROJECTS HERO ══ --}}
    <section class="relative bg-indigo-900 text-white py-20 md:py-32 overflow-hidden">
        {{-- Decorative Background --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-950/80 to-indigo-900/40 z-10"></div>
            <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1920&auto=format&fit=crop"
                class="w-full h-full object-cover scale-105 opacity-60" alt="Projects Hero">
        </div>

        <div class="max-w-[1440px] mx-auto px-4 relative z-20">
            <div class="max-w-3xl">
                <span class="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                    {{ __('Development Works') ?? 'Development Works' }}
                </span>
                <h1 class="text-5xl md:text-7xl font-bold mb-4 leading-none antialiased drop-shadow-2xl tracking-tight">
                    {{ __('projects') }}
                </h1>
                <p class="text-lg md:text-xl text-gray-200 font-medium opacity-90">
                    {{ __('projects_desc') ?? 'Progressing towards a better future through sustainable infrastructure and community initiatives.' }}
                </p>
            </div>
        </div>
        {{-- Bottom fade --}}
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    <div class="max-w-[1440px] mx-auto px-4 py-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($projects as $project)
                <div
                    class="bg-white rounded-[2rem] border border-gray-100 shadow-xl shadow-gray-200/50 overflow-hidden group hover:-translate-y-2 transition-all duration-500">
                    <div class="h-64 relative overflow-hidden">
                        @if($project->cover_image_url)
                            <img src="{{ config('app.url') . $project->cover_image_url }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                alt="{{ $project->title_en }}">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        <div class="absolute bottom-4 left-4">
                            <span
                                class="px-3 py-1 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg">
                                {{ $project->status }}
                            </span>
                        </div>
                    </div>
                    <div class="p-8 flex flex-col h-[calc(100%-16rem)]">
                        <a href="{{ route('projects.show', $project->id) }}" class="block group/link">
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover/link:text-blue-600 transition-colors">
                                {{ $project->{'title_' . $locale} ?? $project->title_en }}
                            </h3>
                        </a>
                        <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 mb-6 font-medium">
                            {{ $project->{'description_' . $locale} ?? $project->description_en }}
                        </p>
                        <div class="mt-auto">
                            <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    {{ __('progress') ?? 'Progress' }}: {{ $project->progress ?? 0 }}%
                                </div>
                                <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-600 rounded-full" style="width: {{ $project->progress ?? 0 }}%"></div>
                                </div>
                            </div>
                            <div class="mt-6 pt-6 border-t border-gray-50">
                                <a href="{{ route('projects.show', $project->id) }}"
                                    class="flex items-center justify-between text-sm font-black uppercase tracking-widest text-blue-600 hover:text-[#f5a623] transition group/btn">
                                    <span>{{ __('view_details') ?? 'View Details' }}</span>
                                    <svg class="w-5 h-5 transition-transform group-hover/btn:translate-x-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-gray-400 font-bold">{{ __('no_projects_listed') ?? 'No projects are currently listed.' }}</p>
                </div>
            @endforelse
            </div>
        </div>
@endsection