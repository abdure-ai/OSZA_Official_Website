@extends('layouts.app')
@section('title', ($post->{'title_' . session('locale', 'en')} ?? $post->title_en) . ' — OSZA News')
@section('content')
    @php $locale = session('locale', 'en'); @endphp

    <div class="max-w-[1440px] mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
                <a href="{{ route('home') }}" class="hover:text-[#1a56db]">Home</a>
                <span>/</span>
                <a href="{{ route('news.index') }}" class="hover:text-[#1a56db]">{{ __('news') }}</a>
                <span>/</span>
                <span
                    class="text-gray-700 truncate">{{ Str::limit($post->{'title_' . $locale} ?? $post->title_en, 50) }}</span>
            </nav>

            {{-- Header --}}
            <div class="mb-6">
                <div class="flex items-center gap-3 text-sm text-gray-500 mb-4">
                    <span
                        class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium uppercase text-xs">{{ $post->category }}</span>
                    <span>{{ $post->published_at?->format('F d, Y') }}</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">
                    {{ $post->{'title_' . $locale} ?? $post->title_en }}</h1>
            </div>

            @if($post->thumbnail_url)
                <div class="mb-8 rounded-2xl overflow-hidden h-72 md:h-96">
                    <img src="{{ asset($post->thumbnail_url) }}"
                        alt="{{ $post->{'title_' . $locale} ?? $post->title_en }}" class="w-full h-full object-cover">
                </div>
            @endif

            {{-- Content --}}
            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed mb-12">
                {!! $post->{'content_' . $locale} ?? $post->content_en !!}
            </div>

            {{-- Related --}}
            @if($related->isNotEmpty())
                <div class="border-t border-gray-100 pt-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Related Articles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($related as $r)
                            <a href="{{ route('news.show', $r->id) }}"
                                class="group flex flex-col bg-gray-50 rounded-xl border border-gray-100 hover:border-[#1a56db]/30 hover:shadow-sm transition overflow-hidden">
                                @if($r->thumbnail_url)
                                    <img src="{{ asset($r->thumbnail_url) }}"
                                        alt="{{ $r->{'title_' . $locale} ?? $r->title_en }}"
                                        class="h-32 w-full object-cover group-hover:scale-105 transition duration-300">
                                @endif
                                <div class="p-4">
                                    <p class="text-xs text-gray-400 mb-1">{{ $r->published_at?->format('M d, Y') }}</p>
                                    <h4
                                        class="text-sm font-semibold text-gray-800 group-hover:text-[#1a56db] line-clamp-2 transition-colors">
                                        {{ $r->{'title_' . $locale} ?? $r->title_en }}</h4>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection