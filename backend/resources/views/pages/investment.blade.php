@extends('layouts.app')
@section('title', 'Investment Opportunities — OSZA')
@section('content')
    <div class="bg-gradient-to-r from-[#1a56db] to-[#1e429f] py-16 text-white text-center">
        <h1 class="text-4xl font-bold mb-4">{{ __('Investment') }}</h1>
        <p class="text-blue-100 max-w-2xl mx-auto px-4">Discover a wealth of opportunities in the Oromo Special Zone. We
            offer a supportive environment for businesses to grow and thrive.</p>
    </div>

    <div class="max-w-[1440px] mx-auto px-4 py-12">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-8">
                <div class="grid sm:grid-cols-2 gap-6">
                    @forelse($investments as $investment)
                        <div
                            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden group hover:shadow-md transition">
                            <div class="relative h-48">
                                @if($investment->image_url)
                                    <img src="{{ config('app.url') . $investment->image_url }}" alt="{{ $investment->title_en }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-blue-50 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-3 left-3">
                                    <span
                                        class="px-2 py-1 bg-white/90 backdrop-blur rounded text-[10px] font-bold text-blue-700 uppercase">{{ $investment->category ?: 'General' }}</span>
                                </div>
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-gray-900 mb-2 truncate group-hover:text-[#1a56db] transition-colors">
                                    <a href="{{ route('investment.show', $investment->id) }}">{{ $investment->{'title_' . session('locale', 'en')} ?? $investment->title_en }}</a>
                                </h3>
                                <p class="text-gray-500 text-xs mb-4 line-clamp-2">
                                    {{ $investment->{'description_' . session('locale', 'en')} ?? $investment->description_en }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-medium text-gray-400">Sector:
                                        {{ $investment->sector ?: 'Mixed' }}</span>
                                    <a href="{{ route('investment.show', $investment->id) }}"
                                        class="text-[#1a56db] font-bold text-xs hover:underline flex items-center gap-1">Details
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg></a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 py-20 text-center text-gray-400">No investment opportunities listed at the
                            moment.</div>
                    @endforelse
                </div>
                <div class="mt-8">
                    {{ $investments->links() }}
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                    <h4 class="font-bold text-gray-900 mb-4">Why Invest Here?</h4>
                    <ul class="space-y-4">
                        <li class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg></div>
                            <div>
                                <p class="font-bold text-sm">Strategic Location</p>
                                <p class="text-xs text-gray-500 mt-1">Conveniently located with access to major markets and
                                    infrastructure.</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg></div>
                            <div>
                                <p class="font-bold text-sm">Rich Resources</p>
                                <p class="text-xs text-gray-500 mt-1">Abundant natural resources and a fertile environment
                                    for agriculture.</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg></div>
                            <div>
                                <p class="font-bold text-sm">Government Support</p>
                                <p class="text-xs text-gray-500 mt-1">Dedicated incentives and support for investors via our
                                    bureau.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="bg-[#1a56db] rounded-2xl p-6 text-white text-center">
                    <h4 class="font-bold mb-2">Ready to Start?</h4>
                    <p class="text-blue-100 text-xs mb-4">Contact our investment bureau for a consultation or to request
                        more information.</p>
                    <a href="{{ route('contact.index') }}"
                        class="inline-block w-full py-2.5 bg-white text-[#1a56db] rounded-xl font-bold text-sm hover:bg-blue-50 transition">Contact
                        Bureau</a>
                </div>
            </div>
        </div>
    </div>
@endsection