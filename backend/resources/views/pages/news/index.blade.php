@extends('layouts.app')
@section('title', __('latest_news') . ' — OSZA')
@section('content')
    @php $locale = session('locale', 'en'); @endphp

    {{-- ═══════════════════════════════════════════ NEWS HERO ══ --}}
    <section class="relative bg-blue-900 text-white py-20 md:py-32 overflow-hidden">
        {{-- Decorative Background --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/40 z-10"></div>
            <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?q=80&w=1920&auto=format&fit=crop" 
                 class="w-full h-full object-cover scale-105 opacity-60" alt="News Hero">
        </div>

        <div class="max-w-[1440px] mx-auto px-4 relative z-20">
            <div class="max-w-3xl">
                <span class="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                    Communications
                </span>
                <h1 class="text-5xl md:text-7xl font-black mb-4 leading-none antialiased drop-shadow-2xl italic tracking-tight">
                    {{ __('latest_news') }}
                </h1>
                <p class="text-lg md:text-xl text-gray-200 font-medium opacity-90">Stay informed with official updates, policy changes, and community stories from across the Zone.</p>
            </div>
        </div>
        {{-- Bottom fade --}}
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    <div class="max-w-[1440px] mx-auto px-4 py-10">
        {{-- Filters --}}
        {{-- Filters Refined --}}
        <form method="GET" action="{{ route('news.index') }}" class="flex flex-wrap items-center gap-4 mb-16 -mt-24 relative z-30 p-8 bg-white rounded-3xl shadow-2xl border border-gray-100">
            <div class="flex-grow min-w-[280px] relative group">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 group-hover:text-[#1a56db] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('search_news') }}"
                    class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#1a56db] focus:bg-white transition-all shadow-inner">
            </div>
            <select name="category"
                class="px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#1a56db] focus:bg-white transition-all min-w-[200px] shadow-inner">
                <option value="">{{ __('all_categories') }}</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit"
                class="px-10 py-4 bg-blue-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-[#1a56db] hover:shadow-xl transition transform active:scale-95">
                Apply Filters
            </button>
            @if(request()->hasAny(['search', 'category']))
                <a href="{{ route('news.index') }}"
                    class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-red-500 transition-colors">Clear</a>
            @endif
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($news as $post)
                <article
                    class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-gray-100 flex flex-col group">
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
                        <h2 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 hover:text-[#1a56db] transition-colors">
                            <a href="{{ route('news.show', $post->id) }}">{{ $post->{'title_' . $locale} ?? $post->title_en }}</a>
                        </h2>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3 flex-grow">
                            {{ Str::limit(strip_tags($post->{'content_' . $locale} ?? $post->content_en), 160) }}</p>
                        <a href="{{ route('news.show', $post->id) }}"
                            class="text-[#1a56db] font-medium hover:underline text-sm mt-auto">{{ __('read_full_story') }} →</a>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-16 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p>No news articles found.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">{{ $news->links() }}</div>
    </div>
@endsection