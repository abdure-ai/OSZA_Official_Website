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
                    class="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                    Visual Archive
                </span>
                <h1
                    class="text-5xl md:text-7xl font-black mb-4 leading-none antialiased drop-shadow-2xl italic tracking-tight">
                    {{ __('photo_gallery') }}
                </h1>
                <p class="text-lg md:text-xl text-gray-200 font-medium opacity-90">A window into the lives, landscapes, and
                    development projects defining the Oromo Special Zone.</p>
            </div>
        </div>
        {{-- Bottom fade --}}
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    <div class="max-w-[1440px] mx-auto px-4 py-10">
        {{-- Category Tabs Refined --}}
        <div class="flex flex-wrap justify-center gap-3 mb-16 -mt-10 relative z-30">
            <a href="{{ route('gallery.index') }}"
                class="px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest border transition-all shadow-xl {{ !$activeCategory ? 'bg-blue-900 text-white border-blue-900 shadow-blue-200' : 'bg-white text-gray-400 border-gray-100 hover:border-blue-900 hover:text-blue-900' }}">All</a>
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
                <div class="break-inside-avoid">
                    <div class="relative group rounded-xl overflow-hidden cursor-pointer"
                        @click="lightbox = '{{ asset($item->image_url) }}'">
                        <img src="{{ asset($item->image_url) }}" alt="{{ $item->title }}"
                            class="w-full object-cover rounded-xl group-hover:scale-105 transition duration-300">
                        <div
                            class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-300 rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </div>
                        @if($item->title)
                            <div
                                class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300 rounded-b-xl">
                                <p class="text-white text-xs font-medium truncate">{{ $item->title }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="col-span-4 text-center text-gray-400 py-16">No photos in this category.</p>
            @endforelse

            {{-- Lightbox Modal --}}
            <div x-show="lightbox" x-transition.opacity
                class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4" @click="lightbox = null"
                style="display:none">
                <img :src="lightbox" class="max-w-full max-h-full rounded-xl object-contain shadow-2xl" @click.stop>
                <button @click="lightbox = null"
                    class="absolute top-4 right-4 text-white bg-white/20 hover:bg-white/40 rounded-full p-2 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endsection