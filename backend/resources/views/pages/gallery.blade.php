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

        {{-- Album Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($albums as $album)
                @php 
                    $title = $album->{'title_' . $locale} ?? $album->title_en;
                    $desc = $album->{'description_' . $locale} ?? $album->description_en;
                @endphp
                <div class="group bg-white rounded-[2.5rem] border-2 border-gray-50 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden cursor-pointer"
                    @click="$dispatch('open-album', { album: {{ json_encode($album->load('items')) }}, index: 0 })"
                    data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    
                    <div class="aspect-[4/3] relative overflow-hidden">
                        <img src="{{ asset($album->cover_image_url) }}" alt="{{ $title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition duration-500"></div>
                        <div class="absolute top-6 left-6">
                            <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest rounded-full border border-white/30">
                                {{ $album->category ?: 'General' }}
                            </span>
                        </div>
                        <div class="absolute bottom-6 right-6">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-xl group-hover:bg-blue-900 group-hover:text-white transition duration-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-3">{{ $title }}</h3>
                        <p class="text-sm text-gray-500 font-medium line-clamp-2 leading-relaxed opacity-80 mb-6">{{ $desc }}</p>
                        <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                            <span class="text-[10px] font-black text-blue-900 uppercase tracking-[0.2em]">{{ $album->items->count() }} Captured Moments</span>
                            <div class="flex -space-x-3">
                                @foreach($album->items->take(3) as $thumb)
                                    <img src="{{ asset($thumb->image_url) }}" class="w-8 h-8 rounded-full border-2 border-white object-cover shadow-sm">
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-32 text-center bg-gray-50 rounded-[3rem] border-4 border-dashed border-gray-100">
                    <p class="text-sm font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('no_albums_found') }}</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Sliding Detail View (Modal Slider) - MOVED OUTSIDE FOR STACKING CONTEXT --}}
    <div x-data="{ 
        selectedAlbum: null,
        imageIndex: 0,
        next() { if (this.selectedAlbum && this.imageIndex < this.selectedAlbum.items.length - 1) this.imageIndex++; else this.imageIndex = 0; },
        prev() { if (this.selectedAlbum && this.imageIndex > 0) this.imageIndex--; else this.imageIndex = this.selectedAlbum.items.length - 1; }
    }" x-init="$watch('selectedAlbum', value => {
        if (value) document.body.style.overflow = 'hidden';
        else document.body.style.overflow = '';
    })" 
    @open-album.window="selectedAlbum = $event.detail.album; imageIndex = $event.detail.index || 0"
    class="relative z-[9999]">
        <template x-if="selectedAlbum">
            <div class="fixed inset-0 bg-black/98 z-[9999] flex flex-col items-center justify-center overflow-hidden"
                @keydown.escape.window="selectedAlbum = null"
                @keydown.right.window="next()"
                @keydown.left.window="prev()"
                x-transition.opacity>
                
                {{-- Header / Navigation --}}
                <div class="absolute top-0 inset-x-0 p-8 flex items-center justify-between z-10 bg-gradient-to-b from-black/80 to-transparent">
                    <div class="text-white">
                        <p class="text-[10px] font-black text-[#f5a623] uppercase tracking-[0.3em] mb-1" x-text="selectedAlbum.category"></p>
                        <h2 class="text-2xl md:text-3xl font-black tracking-tight" x-text="selectedAlbum['title_' + '{{ $locale }}'] || selectedAlbum.title_en"></h2>
                    </div>
                    <button @click="selectedAlbum = null" class="w-16 h-16 bg-white/10 text-white rounded-full flex items-center justify-center hover:bg-white hover:text-black hover:rotate-90 transition-all duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                {{-- Main Slider Content --}}
                <div class="relative w-full h-[75vh] md:h-[80vh] flex items-center justify-center px-4 md:px-12">
                    {{-- Prev Button (Always Visible) --}}
                    <button @click.stop="prev()" class="absolute left-4 md:left-12 w-16 h-16 md:w-24 md:h-24 bg-white/5 text-white rounded-full flex items-center justify-center hover:bg-white hover:text-black transition-all duration-500 z-20 hover:scale-110 active:scale-90 border border-white/10 shadow-2xl">
                        <svg class="w-8 h-8 md:w-12 md:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>

                    {{-- Active Image with Transition --}}
                    <div class="relative w-full h-full flex flex-col items-center justify-center px-2 md:px-20">
                        <div class="relative w-full h-full flex items-center justify-center transition-all duration-700 ease-in-out">
                            <img :src="'{{ asset('') }}' + selectedAlbum.items[imageIndex].image_url" 
                                class="max-w-full max-h-full object-contain rounded-3xl shadow-[0_0_120px_rgba(0,0,0,0.5)] animate-zoom-in"
                                :key="imageIndex">
                        </div>

                        {{-- Image Caption --}}
                        <div class="mt-10 text-center animate-slide-up" :key="'cap-' + imageIndex">
                            <span class="text-[10px] font-black text-[#f5a623] uppercase tracking-[0.4em] mb-3 block" x-text="(imageIndex + 1) + ' / ' + selectedAlbum.items.length"></span>
                            <p class="text-white text-xl md:text-3xl font-black tracking-tight" x-text="selectedAlbum.items[imageIndex]['title_' + '{{ $locale }}'] || selectedAlbum.items[imageIndex].title"></p>
                        </div>
                    </div>

                    {{-- Next Button (Always Visible) --}}
                    <button @click.stop="next()" class="absolute right-4 md:right-12 w-16 h-16 md:w-24 md:h-24 bg-white/5 text-white rounded-full flex items-center justify-center hover:bg-white hover:text-black transition-all duration-500 z-20 hover:scale-110 active:scale-90 border border-white/10 shadow-2xl">
                        <svg class="w-8 h-8 md:w-12 md:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>

                {{-- Thumbnails Navigation --}}
                <div class="w-full max-w-5xl px-8 flex items-center justify-center gap-4 overflow-x-auto py-10 no-scrollbar mt-4">
                    <template x-for="(img, idx) in selectedAlbum.items" :key="idx">
                        <button @click.stop="imageIndex = idx" 
                            :class="imageIndex === idx ? 'border-[#f5a623] scale-110 opacity-100 ring-4 ring-[#f5a623]/20 shadow-2xl shadow-[#f5a623]/20' : 'border-white/10 opacity-20 hover:opacity-100'"
                            class="flex-shrink-0 w-16 h-16 md:w-24 md:h-24 rounded-2xl border-4 overflow-hidden transition-all duration-500">
                            <img :src="'{{ asset('') }}' + img.image_url" class="w-full h-full object-cover">
                        </button>
                    </template>
                </div>
            </div>
        </template>
    </div>
@endsection