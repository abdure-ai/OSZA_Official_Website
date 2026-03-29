@extends('layouts.app')
@section('title', __('tenders') . ' — OSZA')
@section('content')
    @php $locale = session('locale', 'en'); @endphp

    {{-- ═══════════════════════════════════════════ TENDERS HERO ══ --}}
    <section class="relative bg-blue-900 text-white py-20 md:py-32 overflow-hidden">
        {{-- Decorative Background --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/40 z-10"></div>
            <img src="https://images.unsplash.com/photo-1573164713988-8cdad5d97ec7?q=80&w=1920&auto=format&fit=crop"
                class="w-full h-full object-cover scale-105 opacity-60" alt="Tenders Hero">
        </div>

        <div class="max-w-[1440px] mx-auto px-4 relative z-20">
            <div class="max-w-3xl">
                <span
                    class="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                    Procurement
                </span>
                <h1
                    class="text-5xl md:text-7xl font-black mb-4 leading-none antialiased drop-shadow-2xl italic tracking-tight">
                    {{ __('tenders') }}
                </h1>
                <p class="text-lg md:text-xl text-gray-200 font-medium opacity-90">Transparent opportunities for business
                    and community project partnerships.</p>
            </div>
        </div>
        {{-- Bottom fade --}}
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    <div class="max-w-[1440px] mx-auto px-4 py-10">
        <form method="GET" class="flex gap-3 mb-8">
            <select name="status"
                class="px-4 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:ring-2 focus:ring-[#1a56db] focus:outline-none">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Open</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
            <button type="submit"
                class="px-5 py-2 bg-[#1a56db] text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition">Filter</button>
        </form>

        <div class="space-y-4">
            @forelse($tenders as $tender)
                <div
                    class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition p-5 flex flex-col md:flex-row md:items-center gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            @php $isActive = $tender->status === 'active'; @endphp
                            <span
                                class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $isActive ? 'bg-green-100 text-green-700' : ($tender->status === 'closed' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500') }}">{{ ucfirst($tender->status) }}</span>
                            @if($tender->ref_number)
                                <span class="text-xs text-gray-400">REF: {{ $tender->ref_number }}</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-900 text-base">{{ $tender->{'title_' . $locale} ?? $tender->title_en }}
                        </h3>
                        @if($tender->description_en)
                            <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                                {{ $tender->{'description_' . $locale} ?? $tender->description_en }}
                            </p>
                        @endif
                    </div>
                    <div class="flex flex-col items-start md:items-end gap-3 flex-shrink-0 min-w-[160px]">
                        <a href="{{ route('tenders.show', $tender->id) }}"
                            class="w-full text-center px-6 py-2.5 bg-white text-blue-900 border-2 border-blue-900 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-50 transition active:scale-95 shadow-sm">
                            View Detail
                        </a>
                        @if($tender->file_url)
                            <a href="{{ asset($tender->file_url) }}" target="_blank" download
                                class="w-full text-center px-6 py-2.5 bg-blue-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-[#f5a623] hover:text-blue-900 transition active:scale-95 shadow-md flex items-center justify-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 text-gray-400">No tenders available.</div>
            @endforelse
        </div>

        <div class="mt-8">{{ $tenders->links() }}</div>
    </div>
@endsection