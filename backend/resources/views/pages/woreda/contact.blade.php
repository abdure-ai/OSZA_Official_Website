@extends('layouts.app')
@section('title', 'Contact & Directory — ' . ($woreda->name_en) . ' — OSZA')
@section('content')
    @php $locale = session('locale', 'en'); $slug = $woreda->slug; @endphp
    @include('pages.woreda.partials.header')
    @include('pages.woreda.partials.tabs')

    <div class="max-w-[1440px] mx-auto px-4 py-10">
        <div class="grid lg:grid-cols-2 gap-12 items-start">
            {{-- Feedback Form --}}
            <section class="bg-white p-8 md:p-12 rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-blue-900/5 antialiased">
                <h2 class="text-3xl font-black text-gray-900 mb-2 uppercase tracking-tighter">{{ __('send_feedback') }}</h2>
                <p class="text-gray-500 mb-8 font-medium">Have a question or suggestion? We'd love to hear from you.</p>

                <form class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">{{ __('full_name') }}</label>
                            <input type="text" class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-4 font-bold transition-all text-gray-900" placeholder="e.g. Abebe Bikila">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">{{ __('email_address') }}</label>
                            <input type="email" class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-4 font-bold transition-all text-gray-900" placeholder="abebe@example.com">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">{{ __('subject') }}</label>
                        <input type="text" class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-4 font-bold transition-all text-gray-900" placeholder="How can we help?">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">{{ __('message') }}</label>
                        <textarea rows="4" class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-4 font-bold transition-all text-gray-900 resize-none" placeholder="Write your message here..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-[#1a56db] text-white font-black py-4 rounded-2xl shadow-lg shadow-blue-600/30 hover:bg-blue-700 hover:-translate-y-1 transition-all uppercase tracking-widest text-sm">
                        {{ __('send_message') }}
                    </button>
                </form>
            </section>

            {{-- Contact Info Cards --}}
            <div class="space-y-8">
                <section class="bg-gray-900 p-10 md:p-12 rounded-[2.5rem] text-white shadow-2xl relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-black mb-10 uppercase tracking-tighter flex items-center gap-4">
                            <span class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                            </span>
                            {{ __('contact_us') }}
                        </h3>

                        <div class="space-y-8">
                            <div class="flex gap-6 items-start group">
                                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center shrink-0 group-hover:bg-blue-600 transition-colors">
                                    <svg class="w-6 h-6 text-blue-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Phone</p>
                                    <a href="tel:{{ $woreda->contact_phone }}" class="text-xl md:text-2xl font-black hover:text-blue-400 transition-colors">{{ $woreda->contact_phone ?: 'N/A' }}</a>
                                </div>
                            </div>

                            <div class="flex gap-6 items-start group">
                                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center shrink-0 group-hover:bg-blue-600 transition-colors">
                                    <svg class="w-6 h-6 text-blue-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Email</p>
                                    <a href="mailto:{{ $woreda->contact_email }}" class="text-xl md:text-2xl font-black hover:text-blue-400 transition-colors break-all">{{ $woreda->contact_email ?: 'N/A' }}</a>
                                </div>
                            </div>

                            <div class="flex gap-6 items-start group">
                                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center shrink-0 group-hover:bg-blue-600 transition-colors">
                                    <svg class="w-6 h-6 text-blue-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Office</p>
                                    <p class="text-lg font-black leading-tight">{{ $woreda->{'address_' . $locale} ?? $woreda->address_en ?: 'Administrative Office, '.($woreda->{'capital_' . $locale} ?? $woreda->capital_en) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 pt-8 border-t border-white/5">
                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2 leading-relaxed">Office Hours</p>
                            <p class="text-sm font-bold text-gray-400">Monday — Friday: 8:30 AM – 5:30 PM</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        {{-- Map Section --}}
        <section class="mt-16 bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-xl antialiased">
            <h3 class="text-xl font-black text-gray-900 mb-6 uppercase tracking-widest flex items-center gap-3 italic">
                <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span>
                {{ __('woreda_location') }}: {{ $woreda->{'capital_' . $locale} ?? $woreda->capital_en }}
            </h3>
            <div class="w-full h-[500px] rounded-[1.5rem] overflow-hidden border-4 border-gray-50 shadow-inner">
                <iframe 
                    width="100%" 
                    height="100%" 
                    frameborder="0" 
                    scrolling="no" 
                    marginheight="0" 
                    marginwidth="0" 
                    src="https://maps.google.com/maps?q={{ urlencode(($woreda->{'capital_' . $locale} ?? $woreda->capital_en) . ', Ethiopia') }}&t=&z=13&ie=UTF8&iwloc=&output=embed">
                </iframe>
            </div>
        </section>
    </div>
@endsection
