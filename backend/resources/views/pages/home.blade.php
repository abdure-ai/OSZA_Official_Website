@extends('layouts.app')

@section('title', 'Home — Oromo Special Zone Administration')

@section('content')

    {{-- ═══════════════════════════════════════════ HERO SLIDESHOW ══ --}}
    @php 
        $locale = session('locale', 'en'); 
        $heroSlidesData = $heroSlides->map(function($s) {
            $s->media_url = $s->media_url ? asset($s->media_url) : null;
            return $s;
        });
    @endphp
    <div class="relative bg-[#1a56db] text-white overflow-hidden" style="min-height:600px" x-data="{
                                                            current: 0,
                                                            slides: @js($heroSlidesData->isNotEmpty() ? $heroSlidesData : [[]]),
                                                            isAnimating: false,
                                                            timer: null,
                                                            goTo(i) {
                                                                if(this.isAnimating) return;
                                                                this.isAnimating = true;
                                                                this.current = (i + this.slides.length) % this.slides.length;
                                                                setTimeout(() => this.isAnimating = false, 500);
                                                                this.resetTimer();
                                                            },
                                                            next() { this.goTo(this.current + 1); },
                                                            prev() { this.goTo(this.current - 1); },
                                                            resetTimer() { clearTimeout(this.timer); this.timer = setTimeout(() => this.next(), 8000); },
                                                            init() { if(this.slides.length > 1) this.resetTimer(); }
                                                         }">

        {{-- Background media --}}
        <template x-for="(slide, i) in slides" :key="i">
            <div x-show="current === i" class="absolute inset-0 transition-opacity duration-500"
                :class="isAnimating ? 'opacity-0' : 'opacity-100'">
                <template
                    x-if="slide.media_url && (slide.media_type === 'video' || slide.media_url.match(/\.(mp4|webm|ogg|mov)$/i))">
                    <video :src="slide.media_url" class="w-full h-full object-cover" autoplay muted loop playsinline @ended="next()"></video>
                </template>
                <template
                    x-if="slide.media_url && slide.media_type !== 'video' && !slide.media_url.match(/\.(mp4|webm|ogg|mov)$/i)">
                    <img :src="slide.media_url" :alt="slide.title_en" class="w-full h-full object-cover">
                </template>
                <template x-if="!slide.media_url">
                    <div class="absolute inset-0 bg-blue-900"></div>
                </template>
                <div class="absolute inset-0 bg-black/40"></div>
            </div>
        </template>

        {{-- Content --}}
        <div class="max-w-[1440px] mx-auto px-4 py-12 md:py-20 relative z-10">
            <template x-for="(slide, i) in slides" :key="i">
                <div x-show="current === i" x-transition:enter="transition ease-out duration-700 delay-300"
                    x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                    class="max-w-4xl">
                    <span
                        class="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-4 shadow-xl"
                        x-text="slide['cta_text_label'] || 'Zone Updates'"></span>
                    <h1 class="text-4xl md:text-6xl font-black mb-6 leading-[1.1] drop-shadow-2xl antialiased"
                        x-text="slide['title_{{ $locale }}'] || slide.title_en || '{{ __('hero_title') }}'"></h1>
                    <p class="text-xl md:text-2xl mb-10 text-gray-100/90 leading-relaxed font-medium drop-shadow-lg max-w-2xl"
                        x-text="slide['subtitle_{{ $locale }}'] || slide.subtitle_en || '{{ __('hero_subtitle') }}'"></p>
                    <div class="flex flex-wrap gap-5">
                        <a :href="slide.cta_url || '/news'"
                            class="bg-[#f5a623] text-blue-900 font-black py-4 px-10 rounded-full hover:bg-yellow-400 transition transform hover:scale-105 shadow-2xl uppercase tracking-widest text-xs"
                            x-text="slide['cta_text_{{ $locale }}'] || slide.cta_text || '{{ __('our_services') }}'"></a>
                        <a href="{{ route('about.index') }}"
                            class="bg-white/10 border-2 border-white/40 text-white font-black py-4 px-10 rounded-full hover:bg-white hover:text-blue-900 transition transform hover:scale-105 backdrop-blur-md uppercase tracking-widest text-xs">{{ __('learn_more') }}</a>
                    </div>
                </div>
            </template>
        </div>

        {{-- Arrows --}}
        @if($heroSlides->count() > 1)
            <button @click="prev()"
                class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/40 backdrop-blur-sm text-white rounded-full p-3 transition"
                aria-label="Previous slide">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button @click="next()"
                class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/40 backdrop-blur-sm text-white rounded-full p-3 transition"
                aria-label="Next slide">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            {{-- Dots --}}
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2">
                @foreach($heroSlides as $i => $slide)
                    <button @click="goTo({{ $i }})" class="w-2 h-2 rounded-full transition-all duration-300"
                        :class="current === {{ $i }} ? 'bg-[#f5a623] scale-125' : 'bg-white/50 hover:bg-white/80'"
                        aria-label="Slide {{ $i + 1 }}"></button>
                @endforeach
            </div>
        @endif

        {{-- Bottom fade --}}
        <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-gray-50 to-transparent z-10"></div>
    </div>

    {{-- ═══════════════════════════════════════════ QUICK ACCESS ══ --}}
    <section class="py-12 -mt-20 relative z-20 antialiased" x-data>
        <div class="max-w-[1440px] mx-auto px-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-6">
                @php
                    $quickLinks = [
                        [
                            'href' => route('projects.index'),
                            'label' => __('projects'),
                            'desc' => __('development_works'),
                            'color' => 'from-blue-500 to-indigo-600',
                            'lightColor' => 'bg-blue-50',
                            'iconColor' => 'text-blue-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'
                        ],
                        [
                            'href' => route('documents.index'),
                            'label' => __('digital_library'),
                            'desc' => __('digital_resources'),
                            'color' => 'from-emerald-500 to-teal-600',
                            'lightColor' => 'bg-emerald-50',
                            'iconColor' => 'text-emerald-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v20c0 4.418 7.163 8 16 8 1.38 0 2.721-.087 4-.252M8 14c0 4.418 7.163 8 16 8s16-3.582 16-8M8 14c0-4.418 7.163-8 16-8s16 3.582 16 8m0 0v20c0 4.418-7.163 8-16 8a15.53 15.53 0 01-4-.252" />'
                        ],
                        [
                            'href' => route('tenders.index'),
                            'label' => __('tenders'),
                            'desc' => __('procurement'),
                            'color' => 'from-amber-500 to-orange-600',
                            'lightColor' => 'bg-amber-50',
                            'iconColor' => 'text-amber-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'
                        ],
                        [
                            'href' => route('vacancies.index'),
                            'label' => __('vacancies'),
                            'desc' => __('join_our_team'),
                            'color' => 'from-purple-500 to-pink-600',
                            'lightColor' => 'bg-purple-50',
                            'iconColor' => 'text-purple-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'
                        ],
                        [
                            'href' => route('gallery.index'),
                            'label' => __('photo_gallery'),
                            'desc' => __('visual_stories'),
                            'color' => 'from-rose-500 to-red-600',
                            'lightColor' => 'bg-rose-50',
                            'iconColor' => 'text-rose-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>'
                        ],
                        [
                            'href' => route('investment.index'),
                            'label' => __('investment'),
                            'desc' => __('opportunities'),
                            'color' => 'from-cyan-500 to-blue-600',
                            'lightColor' => 'bg-cyan-50',
                            'iconColor' => 'text-cyan-600',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
                        ],
                    ];
                @endphp
                @foreach($quickLinks as $link)
                    <a href="{{ $link['href'] }}"
                        class="group relative bg-white p-6 rounded-[2rem] shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-gray-300/60 transition-all duration-500 hover:-translate-y-2 border border-gray-100 overflow-hidden text-center flex flex-col items-center">

                        {{-- Animated Background Gradient on Hover --}}
                        <div
                            class="absolute inset-0 bg-gradient-to-br {{ $link['color'] }} opacity-0 group-hover:opacity-5 transition-opacity duration-500">
                        </div>

                        <div class="relative z-10 flex flex-col items-center">
                            {{-- Icon Container --}}
                            <div
                                class="w-16 h-16 mb-4 rounded-2xl {{ $link['lightColor'] }} flex items-center justify-center group-hover:scale-110 transition-transform duration-500 shadow-inner">
                                <svg class="w-8 h-8 {{ $link['iconColor'] }} group-hover:scale-110 transition-transform duration-500"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $link['icon'] !!}
                                </svg>
                            </div>

                            <h3
                                class="font-black text-gray-900 text-sm md:text-base uppercase tracking-tight mb-1 group-hover:text-blue-600 transition-colors duration-300">
                                {{ $link['label'] }}
                            </h3>

                            <p
                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest opacity-80 group-hover:opacity-100 transition-opacity leading-tight">
                                {{ $link['desc'] }}
                            </p>

                            {{-- Bottom Decorative Line --}}
                            <div
                                class="mt-4 h-1 w-0 bg-gradient-to-r {{ $link['color'] }} rounded-full group-hover:w-12 transition-all duration-500">
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ ADMIN MESSAGE ══ --}}
    @if($adminMessage && $adminMessage->is_active)
        <section class="py-20 bg-white overflow-hidden">
            <div class="max-w-[1440px] mx-auto px-4">
                <div
                    class="bg-gradient-to-br from-[#1a56db]/5 to-white border border-[#1a56db]/10 rounded-[2rem] p-8 md:p-16 relative">
                    <div
                        class="flex flex-col lg:flex-row gap-12 lg:gap-20 items-center lg:items-start text-center lg:text-left">
                        {{-- Admin Photo --}}
                        <div class="flex-shrink-0 relative w-full lg:w-1/3 flex justify-center lg:justify-start">
                            <div
                                class="w-64 h-64 md:w-80 md:h-80 lg:w-[400px] lg:h-[400px] rounded-3xl overflow-hidden border-8 border-white shadow-2xl relative z-10 bg-gray-100 transform -rotate-2 hover:rotate-0 transition-transform duration-500">
                                @if($adminMessage->photo_url)
                                    <img src="{{ config('app.url') . $adminMessage->photo_url }}" alt="{{ $adminMessage->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 text-[#1a56db]">
                                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            {{-- Decorative Background Circle --}}
                            <div class="absolute -bottom-8 -right-8 w-48 h-48 bg-[#f5a623]/10 rounded-full blur-3xl -z-0"></div>
                            <div class="absolute -top-8 -left-8 w-48 h-48 bg-[#1a56db]/10 rounded-full blur-3xl -z-0"></div>
                        </div>

                        {{-- Message Content --}}
                        <div class="lg:w-2/3 py-4">
                            <div class="inline-flex items-center gap-3 mb-6">
                                <span
                                    class="bg-[#1a56db] text-white text-xs font-bold uppercase tracking-[0.2em] px-5 py-2 rounded-full shadow-lg shadow-[#1a56db]/20">
                                    Official Communication
                                </span>
                            </div>

                            <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-2 leading-tight">
                                {{ $adminMessage->name }}
                            </h2>
                            <p
                                class="text-[#1a56db] font-bold text-lg md:text-xl mb-10 pb-6 border-b-2 border-dashed border-[#1a56db]/10 inline-block">
                                {{ $adminMessage->title_position }}
                            </p>

                            <div class="relative max-w-3xl">
                                <svg class="absolute -top-10 -left-12 w-20 h-20 text-[#1a56db]/10 rotate-180 hidden md:block"
                                    fill="currentColor" viewBox="0 0 32 32">
                                    <path d="M10 8v8H6v-8h4zm12 0v8h-4v-8h4z" />
                                </svg>
                                <div
                                    class="text-gray-700 leading-relaxed text-xl md:text-2xl font-medium italic relative z-10 antialiased">
                                    "{!! nl2br(e($adminMessage->{'message_' . $locale} ?? $adminMessage->message_en)) !!}"
                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════════════════════ LATEST NEWS ══ --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-[1440px] mx-auto px-4">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 border-l-4 border-[#1a56db] pl-4">{{ __('latest_news') }}
                    </h2>
                    <p class="text-gray-500 mt-2 ml-5 text-sm">{{ __('news_subtitle') }}</p>
                </div>
                <a href="{{ route('news.index') }}"
                    class="text-[#1a56db] font-semibold hover:underline hidden md:block text-sm">{{ __('view_all') }} →</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($latestNews as $post)
                    <article
                        class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-gray-100 flex flex-col">
                        <div class="h-48 bg-gray-100 relative overflow-hidden">
                            @if($post->thumbnail_url)
                                <img src="{{ asset($post->thumbnail_url) }}"
                                    alt="{{ $post->{'title_' . $locale} ?? $post->title_en }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                                <span
                                    class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-medium uppercase">{{ $post->category }}</span>
                                <span>{{ $post->published_at?->format('M d, Y') }}</span>
                            </div>
                            <h3
                                class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 hover:text-[#1a56db] transition-colors">
                                <a
                                    href="{{ route('news.show', $post->id) }}">{{ $post->{'title_' . $locale} ?? $post->title_en }}</a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3 flex-grow">
                                {{ Str::limit(strip_tags($post->{'content_' . $locale} ?? $post->content_en), 150) }}
                            </p>
                            <a href="{{ route('news.show', $post->id) }}"
                                class="text-[#1a56db] font-medium hover:underline text-sm mt-auto">{{ __('read_full_story') }}</a>
                        </div>
                    </article>
                @empty
                    <p class="col-span-3 text-center text-gray-400 py-8">{{ __('no_news') }}</p>
                @endforelse
            </div>
            <div class="mt-8 text-center md:hidden">
                <a href="{{ route('news.index') }}"
                    class="text-[#1a56db] font-semibold hover:underline">{{ __('view_all') }} →</a>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ WOREDAS GRID ══ --}}
    <section class="py-16 bg-white">
        <div class="max-w-[1440px] mx-auto px-4">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ __('our_woredas') }}</h2>
                <p class="text-gray-500 text-sm">{{ __('woreda_subtitle') }}</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @forelse($woredas as $woreda)
                    <a href="{{ route('woreda.show', $woreda->slug) }}"
                        class="group relative rounded-2xl overflow-hidden border border-gray-100 hover:border-[#1a56db]/30 shadow-sm hover:shadow-md transition-all bg-gray-50 flex flex-col items-center p-5 text-center">
                        @if($woreda->logo_url)
                            <img src="{{ config('app.url') . $woreda->logo_url }}"
                                alt="{{ $woreda->{'name_' . $locale} ?? $woreda->name_en }}"
                                class="w-14 h-14 rounded-full object-cover mb-3 border-2 border-white shadow">
                        @else
                            <div
                                class="w-14 h-14 rounded-full bg-[#1a56db]/10 flex items-center justify-center mb-3 border-2 border-white shadow">
                                <span
                                    class="text-[#1a56db] font-bold text-xl">{{ strtoupper(substr($woreda->name_en, 0, 1)) }}</span>
                            </div>
                        @endif
                        <span
                            class="font-semibold text-sm text-gray-800 group-hover:text-[#1a56db] transition-colors leading-tight">{{ $woreda->{'name_' . $locale} ?? $woreda->name_en }}</span>
                        @if($woreda->capital_en)
                            <span class="text-xs text-gray-400 mt-1">{{ $woreda->capital_en }}</span>
                        @endif
                    </a>
                @empty
                    <p class="col-span-5 text-center text-gray-400 py-8">No woredas found.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ GALLERY PREVIEW ══ --}}
    @if($galleryItems->isNotEmpty())
        <section class="py-16 bg-gray-50">
            <div class="max-w-[1440px] mx-auto px-4">
                <div class="flex justify-between items-end mb-10">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 border-l-4 border-[#f5a623] pl-4">{{ __('photo_gallery') }}
                        </h2>
                        <p class="text-gray-500 mt-2 ml-5 text-sm">{{ __('gallery_subtitle') }}</p>
                    </div>
                    <a href="{{ route('gallery.index') }}"
                        class="text-[#1a56db] font-semibold hover:underline hidden md:block text-sm">{{ __('view_all') }} →</a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach($galleryItems->take(8) as $item)
                        <div class="relative h-44 rounded-xl overflow-hidden group">
                            <img src="{{ config('app.url') . $item->image_url }}" alt="{{ $item->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @if($item->title)
                                <div
                                    class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-2 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                    <p class="text-white text-xs font-medium truncate">{{ $item->title }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection