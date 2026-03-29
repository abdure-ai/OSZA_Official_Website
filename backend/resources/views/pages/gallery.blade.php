@extends('layouts.app')
@section('title', __('gallery') . ' — OSZA')
@section('content')
    @php $locale = session('locale', 'en'); @endphp

    {{-- ═══════════════════════════════════════════ GALLERY HERO ══ --}}
    <section class="relative bg-blue-900 text-white py-20 md:py-32 overflow-hidden">
        {{-- Decorative Background --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/40 z-10"></div>
            <img src="https://images.unsplash.com/photo-1452421822248-d4c2b47f0c81?q=80&w=1920&auto=format&fit=crop"
                class="w-full h-full object-cover scale-105 opacity-60" alt="Gallery Hero">
        </div>

        <div class="max-w-[1440px] mx-auto px-4 relative z-20">
            <div class="max-w-3xl">
                <span
                    class="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl animate-fade-in">
                    {{ __('visual_archive') }}
                </span>
                <h1
                    class="text-5xl md:text-7xl font-black mb-4 leading-none antialiased drop-shadow-2xl italic tracking-tight">
                    {{ __('photo_gallery') }}
                </h1>
                <p class="text-lg md:text-xl text-gray-200 font-medium opacity-90 animate-slide-up" style="animation-delay: 200ms">
                    {{ __('gallery_subtitle') }}
                </p>
            </div>
        </div>
        {{-- Bottom fade --}}
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    <div class="max-w-[1440px] mx-auto px-4 py-10">
        {{-- Category Tabs Refined --}}
        <div class="flex flex-wrap justify-center gap-3 mb-16 -mt-10 relative z-30">
            <a href="{{ route('gallery.index') }}"
                class="px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest border transition-all shadow-xl {{ !$activeCategory ? 'bg-blue-900 text-white border-blue-900 shadow-blue-200 scale-105' : 'bg-white text-gray-400 border-gray-100 hover:border-blue-900 hover:text-blue-900' }}">
                {{ __('all_categories') }}
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('gallery.index', ['category' => $cat]) }}"
                    class="px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest border transition-all shadow-xl {{ $activeCategory === $cat ? 'bg-blue-900 text-white border-blue-900 shadow-blue-200' : 'bg-white text-gray-400 border-gray-100 hover:border-blue-900 hover:text-blue-900' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        {{-- Photo Grid with lightbox --}}
        <div class="columns-2 sm:columns-3 lg:columns-4 gap-3 space-y-3" x-data="{ lightbox: null }">
            @forelse($items as $item)
                @php 
                    $title = $item->{'title_' . $locale} ?? $item->title;
                @endphp
                <div class="break-inside-avoid animate-fade-in" style="animation-delay: {{ $loop->index * 100 }}ms">
                    <div class="relative group rounded-xl overflow-hidden cursor-pointer"
                        @click="lightbox = { url: '{{ asset($item->image_url) }}', title: '{{ addslashes($title) }}', category: '{{ $item->category }}' }">
                        <img src="{{ asset($item->image_url) }}" alt="{{ $title }}"
                            class="w-full object-cover rounded-xl group-hover:scale-110 transition-all duration-700">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 rounded-xl flex items-center justify-center">
                            <div class="text-center p-4">
                                <svg class="w-10 h-10 text-white mx-auto mb-2 transform scale-50 group-hover:scale-100 transition duration-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                </svg>
                                <p class="text-white text-[10px] font-black uppercase tracking-[0.2em]">{{ __('view_full_image') }}</p>
                            </div>
                        </div>
                        @if($title)
                            <div
                                class="absolute inset-x-0 bottom-0 p-6 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 rounded-b-xl z-10">
                                <span class="inline-block px-2 py-0.5 bg-[#f5a623] text-blue-900 text-[8px] font-black uppercase tracking-widest rounded mb-2">{{ $item->category }}</span>
                                <p class="text-white text-sm font-bold leading-tight">{{ $title }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="col-span-4 text-center text-gray-400 py-16">No photos in this category.</p>
            @endforelse

            {{-- Lightbox Modal Enhanced --}}
            <div x-show="lightbox" x-transition.opacity
                class="fixed inset-0 bg-gray-950/95 z-[100] flex items-center justify-center p-4 md:p-12" 
                @keydown.escape.window="lightbox = null"
                style="display:none">
                
                <div class="relative w-full h-full flex flex-col items-center justify-center" @click="lightbox = null">
                    <div class="relative max-w-5xl w-full h-full flex flex-col" @click.stop>
                        {{-- Image Control --}}
                        <div class="flex-1 relative flex items-center justify-center overflow-hidden">
                            <img :src="lightbox.url" class="max-w-full max-h-full rounded-2xl object-contain shadow-2xl animate-zoom-in">
                        </div>

                        {{-- Metadata Footer --}}
                        <div class="py-8 text-center animate-slide-up">
                            <span class="inline-block px-3 py-1 bg-white/10 text-[#f5a623] text-[10px] font-black uppercase tracking-widest rounded-full mb-3" x-text="lightbox.category"></span>
                            <h2 class="text-white text-xl md:text-3xl font-black tracking-tight leading-tight" x-text="lightbox.title"></h2>
                        </div>
                    </div>
                </div>

                <button @click="lightbox = null"
                    class="absolute top-8 right-8 text-white bg-white/10 hover:bg-white/20 rounded-full w-14 h-14 flex items-center justify-center transition-all hover:rotate-90 z-[110]">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endsection