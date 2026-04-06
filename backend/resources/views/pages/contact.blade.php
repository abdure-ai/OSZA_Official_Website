@extends('layouts.app')
@section('title', __('contact') . ' — OSZA')
@section('content')
    {{-- ═══════════════════════════════════════════ CONTACT HERO ══ --}}
    <section class="relative bg-blue-900 text-white py-20 md:py-32 overflow-hidden">
        {{-- Decorative Background --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/40 z-10"></div>
            <img src="https://images.unsplash.com/photo-1423666639041-f56000c27a9a?q=80&w=1920&auto=format&fit=crop"
                class="w-full h-full object-cover scale-110 opacity-60" alt="Contact Hero">
        </div>

        <div class="max-w-[1440px] mx-auto px-4 relative z-20">
            <div class="max-w-3xl">
                <span
                    class="inline-block px-4 py-1.5 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 shadow-xl">
                    {{ __('get_in_touch') }}
                </span>
                <h1
                    class="text-5xl md:text-7xl font-black mb-4 leading-none antialiased drop-shadow-2xl italic tracking-tight">
                    {{ __('contact') }}
                </h1>
                <p class="text-lg md:text-xl text-gray-200 font-medium opacity-90">{{ __('contact_subtitle') }}</p>
            </div>
        </div>
        {{-- Bottom fade --}}
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    <div class="max-w-[1440px] mx-auto px-4 py-20 bg-gray-50/50">
        <div class="grid lg:grid-cols-5 gap-12 lg:gap-20 items-stretch">
            {{-- Form Section --}}
            <div class="lg:col-span-3">
                <div
                    class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 h-full">
                    <div class="mb-10">
                        <span class="text-[#1a56db] font-bold text-xs uppercase tracking-[0.2em] mb-2 block">{{ __('reach_out') }}</span>
                        <h2 class="text-3xl md:text-4xl font-black text-gray-900">{{ __('send_message') }}</h2>
                        <div class="h-1 w-20 bg-[#f5a623] rounded-full mt-4"></div>
                    </div>

                    @if(session('success'))
                        <div
                            class="mb-8 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-r-xl flex items-start gap-4 shadow-sm">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h4 class="font-bold">{{ __('message_sent_title') }}</h4>
                                <p class="text-sm mt-1 opacity-90">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="group">
                                <label
                                    class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 group-focus-within:text-[#1a56db] transition-colors">{{ __('name') }}
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all duration-300 outline-none text-gray-900 font-medium shadow-inner @error('name') border-red-400 bg-red-50 @enderror"
                                    placeholder="John Doe">
                                @error('name')<p class="text-red-500 text-xs mt-2 font-semibold">{{ $message }}</p>@enderror
                            </div>
                            <div class="group">
                                <label
                                    class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 group-focus-within:text-[#1a56db] transition-colors">{{ __('email') }}
                                    <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all duration-300 outline-none text-gray-900 font-medium shadow-inner @error('email') border-red-400 bg-red-50 @enderror"
                                    placeholder="john@example.com">
                                @error('email')<p class="text-red-500 text-xs mt-2 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="group">
                            <label
                                class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 group-focus-within:text-[#1a56db] transition-colors">{{ __('subject') }}</label>
                            <input type="text" name="subject" value="{{ old('subject') }}"
                                class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all duration-300 outline-none text-gray-900 font-medium shadow-inner"
                                placeholder="{{ __('subject_placeholder') }}">
                        </div>

                        <div class="group">
                            <label
                                class="block text-[11px] font-black uppercase tracking-widest text-gray-500 mb-2 group-focus-within:text-[#1a56db] transition-colors">{{ __('message') }}
                                <span class="text-red-500">*</span></label>
                            <textarea name="message" rows="5" required
                                class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all duration-300 outline-none text-gray-900 font-medium shadow-inner resize-none @error('message') border-red-400 bg-red-50 @enderror"
                                placeholder="{{ __('message_placeholder') }}">{{ old('message') }}</textarea>
                            @error('message')<p class="text-red-500 text-xs mt-2 font-semibold">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit"
                            class="w-full bg-[#1a56db] text-white font-black uppercase tracking-widest text-sm py-4 rounded-2xl hover:bg-blue-800 transition-all duration-300 shadow-xl shadow-blue-500/20 hover:-translate-y-1 hover:shadow-2xl hover:shadow-blue-500/30 flex items-center justify-center gap-3">
                            <span>{{ __('send_message') }}</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Contact Information --}}
            <div class="lg:col-span-2">
                <div
                    class="bg-blue-900 text-white p-8 md:p-12 rounded-[2.5rem] shadow-2xl h-full relative overflow-hidden flex flex-col justify-between">
                    {{-- Decorative elements --}}
                    <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/5 rounded-full blur-3xl pointer-events-none">
                    </div>
                    <div
                        class="absolute -bottom-20 -left-20 w-64 h-64 bg-[#f5a623]/10 rounded-full blur-3xl pointer-events-none">
                    </div>

                    <div class="relative z-10">
                        <h2 class="text-3xl font-black mb-10">{{ __('contact_info') }}</h2>

                        <div class="space-y-8">
                            {{-- Address --}}
                            <div class="flex items-start gap-5 group">
                                <div
                                    class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-[#f5a623] transition-colors duration-300 backdrop-blur-sm border border-white/5">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="pt-1">
                                    <p class="font-bold text-gray-300 text-xs uppercase tracking-widest mb-1">{{ __('office_location') }}</p>
                                    <p class="font-medium text-lg leading-snug">
                                        {!! nl2br(e($settings->address ?? 'Oromo Special Zone Administration, Kemise')) !!}
                                    </p>
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="flex items-start gap-5 group">
                                <div
                                    class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-[#f5a623] transition-colors duration-300 backdrop-blur-sm border border-white/5">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="pt-1">
                                    <p class="font-bold text-gray-300 text-xs uppercase tracking-widest mb-1">{{ __('phone_number') }}
                                    </p>
                                    <p class="font-medium text-lg">{{ $settings->phone ?? '+251 33 111 2222' }}</p>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="flex items-start gap-5 group">
                                <div
                                    class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-[#f5a623] transition-colors duration-300 backdrop-blur-sm border border-white/5">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="pt-1">
                                    <p class="font-bold text-gray-300 text-xs uppercase tracking-widest mb-1">{{ __('email_address') }}
                                    </p>
                                    <p class="font-medium text-lg break-all">{{ $settings->email ??
                                        'info@oromospecialzone.gov.et' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Working Hours --}}
                    <div class="relative z-10 mt-12 pt-8 border-t border-white/10">
                        <h4 class="font-bold text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('working_hours') }}
                        </h4>
                        <div class="font-medium text-sm text-gray-300 mb-2 leading-relaxed">
                            {!! nl2br(e($settings->working_hours ?? "Monday - Friday: 8:00 AM - 5:30 PM\nSaturday - Sunday: Closed")) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ MAP SECTION ══ --}}
    <section class="h-[500px] w-full bg-gray-200 relative">
        @if(isset($settings->map_url) && !empty($settings->map_url))
            <div class="absolute inset-0 w-full h-full [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:absolute [&>iframe]:inset-0 [&>iframe]:border-0 [&>iframe]:grayscale hover:[&>iframe]:grayscale-0 [&>iframe]:transition-all [&>iframe]:duration-700">
                {!! $settings->map_url !!}
            </div>
        @else
            <iframe
                src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=Kemise,+Oromia+Special+Zone,+Amhara,+Ethiopia&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
                class="absolute inset-0 w-full h-full border-0 grayscale hover:grayscale-0 transition-all duration-700"
                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        @endif
    </section>
@endsection