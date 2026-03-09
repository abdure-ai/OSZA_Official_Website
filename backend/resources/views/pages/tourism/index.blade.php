@extends('layouts.app')

@section('title', __('Visit Oromo Special Zone') . ' — Tourism')

@section('content')
    @php $locale = session('locale', 'en'); @endphp

    {{-- ═══════════════════════════════════════════ TOURISM HERO SLIDESHOW ══ --}}
    <div class="relative bg-[#1a56db] text-white overflow-hidden" style="min-height:600px" x-data="{
                                                        current: 0,
                                                        slides: {{ $heroSlides->isNotEmpty() ? $heroSlides->toJson() : '[{}]' }},
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
                    <video
                        :src="slide.media_url.startsWith('http') ? slide.media_url : (slide.media_url.startsWith('/') ? slide.media_url : '/' + slide.media_url)"
                        class="w-full h-full object-cover" autoplay muted loop playsinline @ended="next()"></video>
                </template>
                <template
                    x-if="slide.media_url && slide.media_type !== 'video' && !slide.media_url.match(/\.(mp4|webm|ogg|mov)$/i)">
                    <img :src="slide.media_url.startsWith('http') ? slide.media_url : (slide.media_url.startsWith('/') ? slide.media_url : '/' + slide.media_url)"
                        :alt="slide.title_en" class="w-full h-full object-cover">
                </template>
                <template x-if="!slide.media_url">
                    <div class="absolute inset-0 bg-blue-900">
                        <img src='https://images.unsplash.com/photo-1543163521-1bf539c55dd2?q=80&w=1920&auto=format&fit=crop'
                            class="w-full h-full object-cover opacity-60">
                    </div>
                </template>
                <div class="absolute inset-0 bg-black/40"></div>
            </div>
        </template>

        {{-- Content --}}
        <div class="max-w-[1440px] mx-auto px-4 py-12 md:py-20 relative z-10">
            <template x-for="(slide, i) in slides" :key="i">
                <div x-show="current === i" x-transition:enter="transition ease-out duration-700 delay-300"
                    x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                    class="max-w-4xl w-full">
                    <span
                        class="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl"
                        x-text="slide['cta_text_label'] || 'Discover Our Heritage'"></span>
                    <h1 class="text-4xl md:text-7xl font-black mb-6 leading-tight drop-shadow-2xl antialiased"
                        x-html="(slide['title_{{ $locale }}'] || slide.title_en || 'Visit Oromo<br><span class=\'text-[#f5a623]\'>Special Zone</span>')">
                    </h1>
                    <p class="text-lg md:text-2xl mb-10 text-gray-100/90 leading-relaxed font-medium drop-shadow-lg"
                        x-text="slide['subtitle_{{ $locale }}'] || slide.subtitle_en || 'Experience the breathtaking landscapes, ancient history, and vibrant culture of the heart of Ethiopia. Your journey into the extraordinary begins here.'">
                    </p>
                    <div class="flex flex-wrap gap-5">
                        <a :href="slide.cta_url || '#destinations'"
                            class="bg-white text-blue-900 font-bold py-4 px-10 rounded-full hover:bg-[#f5a623] hover:text-white transition transform hover:scale-105 shadow-xl"
                            x-text="slide['cta_text_{{ $locale }}'] || slide.cta_text || 'Explore Destinations'"></a>
                        <a href="{{ route('contact.index') }}"
                            class="bg-transparent border-2 border-white/50 text-white font-bold py-4 px-10 rounded-full hover:bg-white/10 transition transform hover:scale-105 backdrop-blur-sm">Plan
                            Your Trip</a>
                    </div>
                </div>
            </template>
        </div>

        {{-- Arrows --}}
        @if($heroSlides->count() > 1)
            <button @click="prev()"
                class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/40 backdrop-blur-sm text-white rounded-full p-3 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button @click="next()"
                class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/40 backdrop-blur-sm text-white rounded-full p-3 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            {{-- Dots --}}
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2">
                @foreach($heroSlides as $i => $slide)
                    <button @click="goTo({{ $i }})" class="w-3 h-3 rounded-full transition-all duration-300"
                        :class="current === {{ $i }} ? 'bg-[#f5a623] scale-125' : 'bg-white/50 hover:bg-white/80'"></button>
                @endforeach
            </div>
        @endif

        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent z-10"></div>
    </div>

    {{-- ═══════════════════════════════════════════ DESTINATIONS SECTION ══ --}}
    <section id="destinations" class="py-20 bg-gray-50">
        <div class="max-w-[1440px] mx-auto px-4">
            {{-- Filter Bar --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-8 mb-16">
                <div>
                    <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-3">{{ __('Major Destinations') }}</h2>
                    <div class="h-1.5 w-24 bg-[#f5a623] rounded-full"></div>
                </div>

                <div class="flex flex-wrap justify-center gap-2">
                    <a href="{{ route('tourism.index') }}"
                        class="px-6 py-2 rounded-full text-sm font-bold transition-all {{ !request('category') ? 'bg-blue-900 text-white shadow-lg shadow-blue-200' : 'bg-white text-gray-500 hover:bg-gray-100 border border-gray-100' }}">
                        All
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('tourism.index', ['category' => $cat]) }}"
                            class="px-6 py-2 rounded-full text-sm font-bold transition-all {{ request('category') == $cat ? 'bg-blue-900 text-white shadow-lg shadow-blue-200' : 'bg-white text-gray-500 hover:bg-gray-100 border border-gray-100' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($sites as $site)
                    <a href="{{ route('tourism.show', $site->slug) }}"
                        class="group flex flex-col bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                        <div class="h-80 relative overflow-hidden">
                            @if($site->cover_image_url)
                                <img src="{{ config('app.url') . $site->cover_image_url }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-blue-50 flex items-center justify-center text-blue-200">
                                    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div
                                class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent p-6 pt-20">
                                <span
                                    class="bg-[#f5a623] text-blue-900 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-2 inline-block">
                                    {{ $site->category ?: 'Explore' }}
                                </span>
                                <h3 class="text-white text-2xl font-black mb-1 leading-tight">
                                    {{ $site->{'name_' . $locale} ?? $site->name_en }}</h3>
                                <div class="flex items-center gap-1.5 text-gray-300 text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $site->woreda?->name_en ?: 'Visit OSZA' }}
                                </div>
                            </div>
                        </div>
                        <div class="p-8 flex flex-col flex-grow">
                            <p class="text-gray-500 text-sm mb-6 line-clamp-3 leading-relaxed">
                                {{ Str::limit(strip_tags($site->{'description_' . $locale} ?? $site->description_en), 120) }}
                            </p>
                            <div class="mt-auto flex items-center justify-between">
                                <span
                                    class="text-[#1a56db] font-black text-xs uppercase tracking-widest group-hover:translate-x-1 transition-transform">
                                    Learn More →
                                </span>
                                <div class="flex -space-x-2">
                                    <div
                                        class="w-8 h-8 rounded-full border-2 border-white bg-blue-50 flex items-center justify-center text-[10px] font-black text-blue-900">
                                        12+</div>
                                    <div
                                        class="w-8 h-8 rounded-full border-2 border-white bg-green-50 flex items-center justify-center text-green-600">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M12 4v16" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No Destinations Found</h3>
                        <p class="text-gray-500">We are currently cataloging more breathtaking sites. Check back soon!</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-16">
                {{ $sites->links() }}
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ TRAVEL TIPS ══ --}}
    <section class="py-20 bg-white border-t border-gray-100">
        <div class="max-w-[1440px] mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="flex items-start gap-5">
                    <div
                        class="w-16 h-16 bg-[#f5a623]/10 rounded-2xl flex items-center justify-center flex-shrink-0 text-[#f5a623]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">Best Time to Visit</h4>
                        <p class="text-gray-500 text-sm leading-relaxed">September to March offers the most pleasant climate
                            for outdoor adventure and cultural festivals.</p>
                    </div>
                </div>
                <div class="flex items-start gap-5">
                    <div
                        class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center flex-shrink-0 text-[#1a56db]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">Local Guides</h4>
                        <p class="text-gray-500 text-sm leading-relaxed">We recommend certified local guides to enrich your
                            experience with deep historical context and hidden gems.</p>
                    </div>
                </div>
                <div class="flex items-start gap-5">
                    <div
                        class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center flex-shrink-0 text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">Travel Safety</h4>
                        <p class="text-gray-500 text-sm leading-relaxed">Oromo Special Zone is known for its hospitality and
                            peace. We provide 24/7 travel support for all registered visitors.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection