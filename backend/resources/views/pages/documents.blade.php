@extends('layouts.app')
@section('title', __('documents') . ' — OSZA')
@section('content')
    @php $locale = session('locale', 'en'); @endphp

    {{-- ═══════════════════════════════════════════ DOCUMENTS HERO ══ --}}
    <section class="relative bg-blue-900 text-white py-20 md:py-32 overflow-hidden">
        {{-- Decorative Background --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/40 z-10"></div>
            <img src="https://images.unsplash.com/photo-1544377193-33dcf4d68fb5?q=80&w=1920&auto=format&fit=crop"
                class="w-full h-full object-cover scale-105 opacity-60" alt="Documents Hero">
        </div>

        <div class="max-w-[1440px] mx-auto px-4 relative z-20">
            <div class="max-w-3xl">
                <span
                    class="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                    Public Archive
                </span>
                <h1
                    class="text-5xl md:text-7xl font-black mb-4 leading-none antialiased drop-shadow-2xl italic tracking-tight">
                    {{ __('documents') }}
                </h1>
                <p class="text-lg md:text-xl text-gray-200 font-medium opacity-90">Access official publications, legislative
                    reports, and development policy documents.</p>
            </div>
        </div>
        {{-- Bottom fade --}}
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    <div class="max-w-[1440px] mx-auto px-4 py-10">
        {{-- Filters --}}
        <form method="GET" action="{{ route('documents.index') }}" class="flex flex-wrap gap-3 mb-8">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('search') }}"
                class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db] w-64">
            <select name="category"
                class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db] bg-white">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit"
                class="px-5 py-2 bg-[#1a56db] text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition">Filter</button>
        </form>

        <div class="grid grid-cols-3 md:grid-cols-5 lg:grid-cols-7 xl:grid-cols-9 gap-5">
            @forelse($documents as $doc)
                <div class="group flex flex-col">
                    {{-- Book Card --}}
                    <div
                        class="relative aspect-[3/4] bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden group-hover:shadow-2xl group-hover:-translate-y-2 transition-all duration-500">
                        @if($doc->cover_image_url)
                            <img src="{{ asset($doc->cover_image_url) }}"
                                alt="{{ $doc->{'title_' . $locale} ?? $doc->title_en }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-blue-50 to-indigo-50 flex flex-col items-center justify-center p-6 text-center group-hover:from-indigo-100 transition-colors">
                                <div
                                    class="text-5xl mb-6 opacity-40 filter drop-shadow-xl group-hover:scale-125 transition-transform">
                                    <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <span
                                    class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-400 group-hover:text-indigo-600 transition-colors">
                                    Official Digital Resource
                                </span>
                                <div class="absolute inset-0 border-8 border-white/30 rounded-[2rem] pointer-events-none"></div>
                            </div>
                        @endif

                        {{-- Overlay Type Tag --}}
                        <div
                            class="absolute top-4 right-4 bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl z-20">
                            {{ pathinfo($doc->file_url, PATHINFO_EXTENSION) ?: 'PDF' }}
                        </div>

                        {{-- Hover Quick Actions --}}
                        <div
                            class="absolute inset-0 bg-blue-900/60 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center transition-all duration-500 p-6 text-center backdrop-blur-sm z-30">
                            <div class="flex flex-col gap-3 w-full max-w-[160px]">
                                @if($doc->file_url)
                                    <a href="{{ asset($doc->file_url) }}" target="_blank" rel="noopener"
                                        class="bg-white text-blue-900 px-6 py-3 rounded-full font-black text-[10px] uppercase tracking-widest shadow-2xl transform translate-y-8 group-hover:translate-y-0 transition-all duration-500 flex items-center justify-center gap-2 hover:bg-gray-100 active:scale-95">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Read Online
                                    </a>
                                    <a href="{{ asset($doc->file_url) }}" download
                                        class="bg-[#f5a623] text-blue-900 px-6 py-3 rounded-full font-black text-[10px] uppercase tracking-widest shadow-2xl transform translate-y-12 group-hover:translate-y-0 transition-all duration-700 flex items-center justify-center gap-2 hover:scale-105 active:scale-95">
                                        Download
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="mt-6 flex flex-col flex-grow">
                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-2 block">
                            {{ $doc->category }}
                        </span>
                        <h3
                            class="font-bold text-gray-900 leading-tight line-clamp-2 group-hover:text-blue-600 transition-colors cursor-default mb-2">
                            {{ $doc->{'title_' . $locale} ?? $doc->title_en }}
                        </h3>
                        <p class="text-xs text-gray-400 line-clamp-1 italic font-medium">
                            {{ $doc->author ?: 'Oromo Special Zone Administration' }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-32 bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                    <p class="text-gray-400 font-bold italic">No documents found in the archives.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-16">{{ $documents->links() }}</div>
    </div>
@endsection