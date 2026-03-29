@extends('layouts.app')

@section('title', ($site->{'name_' . session('locale', 'en')} ?? $site->name_en) . ' — Tourism')

@section('content')
    @php $locale = session('locale', 'en'); @endphp

    {{-- ═══════════════════════════════════════════ DESTINATION HERO ══ --}}
    <section class="relative h-[70vh] md:h-[85vh] overflow-hidden flex items-end pb-20">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent z-10"></div>
            @if($site->cover_image_url)
                <img src="{{ asset($site->cover_image_url) }}" class="w-full h-full object-cover scale-105"
                    alt="{{ $site->name_en }}">
            @else
                <div class="w-full h-full bg-blue-900"></div>
            @endif
        </div>

        <div class="max-w-[1440px] mx-auto px-4 relative z-20 w-full text-white">
            <nav class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-300 mb-6">
                <a href="{{ route('tourism.index') }}" class="hover:text-[#f5a623] transition">Tourism</a>
                <span>/</span>
                <span class="text-[#f5a623]">{{ $site->category ?: 'Explore' }}</span>
            </nav>
            <h1 class="text-5xl md:text-8xl font-black mb-4 leading-none antialiased">
                {{ $site->{'name_' . $locale} ?? $site->name_en }}
            </h1>
            <div class="flex flex-wrap items-center gap-6">
                <div class="flex items-center gap-2 text-[#f5a623] font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>{{ $site->woreda?->name_en ?? 'Oromo Special Zone' }}</span>
                </div>
                <div class="w-px h-6 bg-white/20 hidden md:block"></div>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium border border-white/30 px-3 py-1 rounded-full backdrop-blur-sm">Open
                        24/7</span>
                    <span class="text-sm font-medium border border-white/30 px-3 py-1 rounded-full backdrop-blur-sm">Entry:
                        Free</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ STORYTELLING SECTION ══ --}}
    <section class="py-24 bg-white relative">
        {{-- Floating Badge --}}
        <div
            class="absolute -top-12 right-12 hidden lg:flex items-center justify-center w-24 h-24 bg-[#f5a623] rounded-full shadow-2xl animate-bounce">
            <span class="text-blue-900 text-[10px] font-black uppercase text-center leading-tight">Heritage<br>Site</span>
        </div>

        <div class="max-w-[1440px] mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-start">
                <div class="space-y-8">
                    <div
                        class="inline-block px-5 py-2 bg-blue-50 text-[#1a56db] text-xs font-black uppercase tracking-widest rounded-full">
                        Introduction
                    </div>
                    <h2 class="text-3xl md:text-5xl font-black text-gray-900 leading-tight">
                        Experience the Essence of {{ $site->{'name_' . $locale} ?? $site->name_en }}
                    </h2>
                    <div
                        class="prose prose-xl text-gray-600 font-medium leading-relaxed max-w-none antialiased translate-y-0 opacity-100">
                        {!! nl2br(e($site->{'description_' . $locale} ?? $site->description_en)) !!}
                    </div>

                    <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 mt-12">
                        <h4 class="text-lg font-black text-gray-900 mb-4">Location Details</h4>
                        <ul class="space-y-4 text-sm text-gray-600 font-bold">
                            <li class="flex items-center gap-3">
                                <span
                                    class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#f5a623] shadow-sm">📍</span>
                                <span>{{ $site->location_name_en ?: 'Located in ' . ($site->woreda?->name_en ?? 'Special Zone') }}</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span
                                    class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-blue-600 shadow-sm">🗺️</span>
                                <span>Coordinates: {{ $site->latitude ?? 'N/A' }}, {{ $site->longitude ?? 'N/A' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Hero Media Beside Description --}}
                <div
                    class="sticky top-24 rounded-3xl overflow-hidden shadow-2xl h-[400px] md:h-[600px] border-4 border-white">
                    @if($site->cover_image_url && preg_match('/\.(mp4|mov|webm)$/i', $site->cover_image_url))
                        <video src="{{ asset($site->cover_image_url) }}" class="w-full h-full object-cover"
                            autoplay muted loop playsinline></video>
                    @elseif($site->cover_image_url)
                        <img src="{{ asset($site->cover_image_url) }}"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-700"
                            alt="{{ $site->name_en }}">
                    @else
                        <div
                            class="w-full h-full bg-gray-100 flex items-center justify-center border-4 border-dashed border-gray-200">
                            <div class="text-center">
                                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Media Pending</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ LIGHTBOX GALLERY ══ --}}
    @if($site->gallery_urls && count($site->gallery_urls) > 0)
        <section class="py-24 bg-gray-50 overflow-hidden" x-data="{ 
                    lightboxOpen: false, 
                    activeImage: '',
                    images: {{ json_encode(array_map(fn($url) => asset($url), $site->gallery_urls)) }},
                    currentIndex: 0,
                    openLightbox(index) {
                        this.currentIndex = index;
                        this.activeImage = this.images[index];
                        this.lightboxOpen = true;
                        document.body.style.overflow = 'hidden';
                    },
                    closeLightbox() {
                        this.lightboxOpen = false;
                        document.body.style.overflow = '';
                    },
                    nextImage() {
                        this.currentIndex = (this.currentIndex + 1) % this.images.length;
                        this.activeImage = this.images[this.currentIndex];
                    },
                    prevImage() {
                        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                        this.activeImage = this.images[this.currentIndex];
                    }
                }" @keydown.escape.window="closeLightbox()" @keydown.arrow-right.window="lightboxOpen && nextImage()"
            @keydown.arrow-left.window="lightboxOpen && prevImage()">

            <div class="max-w-[1440px] mx-auto px-4 mb-12 text-center">
                <h3 class="text-3xl font-black text-gray-900 mb-2">Photo Gallery</h3>
                <p class="text-gray-500 font-bold uppercase tracking-widest text-xs">Visual Exploration</p>
            </div>

            <div class="max-w-[1440px] mx-auto px-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <template x-for="(image, index) in images" :key="index">
                    <div @click="openLightbox(index)"
                        class="relative h-64 rounded-3xl overflow-hidden group cursor-zoom-in shadow-sm hover:shadow-xl transition-all">
                        <template x-if="image.match(/\.(mp4|webm|ogg|mov)$/i)">
                            <video :src="image" class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                muted loop></video>
                        </template>
                        <template x-if="!image.match(/\.(mp4|webm|ogg|mov)$/i)">
                            <img :src="image" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </template>
                        <div
                            class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-lg"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Lightbox Overlay --}}
            <div x-show="lightboxOpen" style="display: none;"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 backdrop-blur-sm" x-transition.opacity>
                {{-- Close Btn --}}
                <button @click="closeLightbox()" class="absolute top-6 right-6 text-white/50 hover:text-white transition z-50">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Image/Video Container --}}
                <div class="relative w-full h-full max-w-6xl max-h-screen p-12 flex items-center justify-center"
                    @click.outside="closeLightbox()">
                    <button @click.stop="prevImage()" class="absolute left-6 text-white/50 hover:text-white transition p-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <div class="w-full h-full flex items-center justify-center relative">
                        {{-- Loading spinner --}}
                        <div class="absolute inset-0 flex items-center justify-center -z-10 text-white/20">
                            <svg class="animate-spin w-12 h-12" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>

                        <template x-if="activeImage && activeImage.match(/\.(mp4|webm|ogg|mov)$/i)">
                            <video :src="activeImage" class="max-w-full max-h-full object-contain rounded-xl shadow-2xl"
                                controls autoplay></video>
                        </template>
                        <template x-if="activeImage && !activeImage.match(/\.(mp4|webm|ogg|mov)$/i)">
                            <img :src="activeImage" class="max-w-full max-h-full object-contain rounded-xl shadow-2xl"
                                x-transition>
                        </template>
                    </div>

                    <button @click.stop="nextImage()" class="absolute right-6 text-white/50 hover:text-white transition p-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    {{-- Counter --}}
                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/50 font-bold tracking-widest text-sm">
                        <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════════════════════ RELATED DESTINATIONS ══ --}}
    @if($related->isNotEmpty())
        <section class="py-24 bg-white">
            <div class="max-w-[1440px] mx-auto px-4">
                <h3 class="text-3xl font-black text-gray-900 mb-12 flex items-center gap-4">
                    Explore Nearby
                    <div class="h-1 flex-grow bg-gray-100 rounded-full"></div>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($related as $rel)
                        <a href="{{ route('tourism.show', $rel->slug) }}" class="group block">
                            <div class="h-72 rounded-[2rem] overflow-hidden mb-6 shadow-md shadow-blue-900/5">
                                <img src="{{ asset($rel->cover_image_url) }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </div>
                            <h4 class="text-xl font-black text-gray-900 group-hover:text-blue-600 transition">
                                {{ $rel->{'name_' . $locale} ?? $rel->name_en }}</h4>
                            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-1">
                                {{ $rel->category ?: 'Explore' }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection