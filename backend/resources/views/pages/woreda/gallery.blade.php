@extends('layouts.app')
@section('title', 'Gallery — ' . ($woreda->name_en) . ' — OSZA')
@section('content')
    @php $locale = session('locale', 'en');
    $slug = $woreda->slug; @endphp
    @include('pages.woreda.partials.header')
    @include('pages.woreda.partials.tabs')

    <div class="max-w-[1440px] mx-auto px-4 py-10" x-data="{ activeCategory: '{{ $activeCategory }}' }">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Photo Gallery</h2>
                <p class="text-gray-500 text-sm">Visual tour of our woreda's landmarks and community.</p>
            </div>
            @if($categories->count() > 1)
                <div class="flex gap-2">
                    @foreach($categories as $category)
                        <a href="{{ route('woreda.gallery', ['slug' => $slug, 'category' => $category]) }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $activeCategory === $category ? 'bg-[#1a56db] text-white shadow-md' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            {{ $category ?: 'General' }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        @if($items->isNotEmpty())
            <div class="columns-1 sm:columns-2 lg:columns-3 gap-4 space-y-4">
                @foreach($items as $item)
                    <div class="break-inside-avoid relative rounded-2xl overflow-hidden group border border-gray-100 shadow-sm cursor-pointer"
                        @click="$dispatch('open-lightbox', { img: '{{ config('app.url') . $item->image_url }}', title: '{{ $item->title }}' })">
                        <img src="{{ config('app.url') . $item->image_url }}" alt="{{ $item->title }}"
                            class="w-full h-auto object-cover group-hover:scale-105 transition duration-500">
                        <div
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                            <div>
                                <p class="text-white font-bold text-sm">{{ $item->title }}</p>
                                <p class="text-white/70 text-[10px]">{{ $item->category ?: 'General' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-24 text-center">
                <div
                    class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-dashed border-gray-200">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="text-gray-400 font-medium">No photos found in this category.</p>
            </div>
        @endif
    </div>

    {{-- Simple Alpine Lightbox --}}
    <div x-data="{ open: false, img: '', title: '' }" x-show="open"
        @open-lightbox.window="open = true; img = $event.detail.img; title = $event.detail.title"
        @keydown.escape.window="open = false" class="fixed inset-0 z-[100] bg-black/90 flex items-center justify-center p-4"
        x-cloak>
        <button @click="open = false" class="absolute top-6 right-6 text-white/70 hover:text-white transition">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="max-w-5xl w-full">
            <img :src="img" class="w-full h-auto max-h-[85vh] object-contain rounded-lg shadow-2xl">
            <p x-text="title" class="text-center text-white mt-4 font-bold text-lg"></p>
        </div>
    </div>
@endsection