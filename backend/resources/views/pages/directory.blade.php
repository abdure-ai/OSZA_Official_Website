@php $locale = session('locale', 'en'); @endphp
@extends('layouts.app')
@section('title', __('directory') . ' — OSZA')
@section('content')
    <div class="bg-[#1a56db] text-white py-10">
        <div class="max-w-[1440px] mx-auto px-4">
            <h1 class="text-4xl font-bold">{{ __('directory') }}</h1>
            <p class="text-blue-200 mt-2 text-sm">Official staff and department contact directory.</p>
        </div>
    </div>
    <div class="max-w-[1440px] mx-auto px-4 py-10">
        <form method="GET" class="mb-8 flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or department..."
                class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db] w-72">
            <button type="submit"
                class="px-5 py-2 bg-[#1a56db] text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition">Search</button>
        </form>
        @php $grouped = $records->groupBy('department'); @endphp
        @forelse($grouped as $dept => $members)
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">{{ $dept ?: 'General' }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($members as $record)
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
                            @if($record->photo_url)
                                <img src="{{ config('app.url') . $record->photo_url }}" alt="{{ $record->name }}"
                                    class="w-12 h-12 rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-full bg-[#1a56db]/10 flex items-center justify-center flex-shrink-0">
                                    <span class="text-[#1a56db] font-bold">{{ strtoupper(substr($record->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900 text-sm">{{ $record->name }}</p>
                                <p class="text-xs text-[#1a56db] font-medium">{{ $record->title }}</p>
                                @if($record->phone)
                                <p class="text-xs text-gray-500 mt-0.5">📞 {{ $record->phone }}</p>@endif
                                @if($record->email)
                                <p class="text-xs text-gray-500 truncate">✉ {{ $record->email }}</p>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-gray-400">No directory records found.</div>
        @endforelse
    </div>
@endsection