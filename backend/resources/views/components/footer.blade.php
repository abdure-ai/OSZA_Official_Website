<footer class="bg-[#0b0f19] text-white pt-24 pb-12 relative overflow-hidden">
    {{-- Decorative Background Element --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-[#1a56db]/5 blur-[120px] rounded-full -mr-48 -mt-48"></div>

    <div class="max-w-[1440px] mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-16 mb-20">
            {{-- Column 1: Branding --}}
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-8">
                    @if($settings->footer_logo)
                        <img src="{{ asset('storage/' . $settings->footer_logo) }}" class="h-12 w-auto" alt="Footer Logo">
                    @else
                        <img src="https://upload.wikimedia.org/wikipedia/commons/e/ea/Coat_of_arms_of_Ethiopia.svg"
                            class="h-12 w-auto brightness-0 invert" alt="National Emblem">
                    @endif
                    <div class="border-l-2 border-white/10 pl-3">
                        <div class="text-[10px] font-black uppercase tracking-[0.2em] text-[#f5a623]">{{ __('official_portal') }}
                        </div>
                        <div class="text-sm font-black uppercase tracking-tighter leading-tight italic">{{ __('title_main') }}<br>{{ __('title_sub') }}</div>
                    </div>
                </div>
                <p class="text-gray-500 text-sm leading-relaxed font-medium mb-8">
                    {{ __('footer_branding_subtitle') }}
                </p>
                <div class="flex gap-4">
                    @php
                        $socials = [
                            ['url' => $settings->facebook_url ?? '#', 'icon' => 'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z', 'label' => 'Facebook'],
                            ['url' => $settings->twitter_url ?? '#', 'icon' => 'M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z', 'label' => 'Twitter'],
                            ['url' => $settings->telegram_url ?? '#', 'icon' => 'M21.198 2.433a2.242 2.242 0 00-1.022.215l-16.5 7.07c-1.12.48-.87 2.07.37 2.19l4.08.5 1.69 5.06c.24.74 1.15.98 1.73.45l2.3-2.07 4.36 3.14c.8.57 1.95.14 2.18-.82l3.16-13.43c.28-1.19-.83-2.19-2.37-1.88z', 'label' => 'Telegram'],
                        ];
                    @endphp
                    @foreach($socials as $social)
                        @if($social['url'] && $social['url'] !== '#')
                            <a href="{{ $social['url'] }}"
                                class="w-10 h-10 rounded-2xl bg-white/5 flex items-center justify-center hover:bg-[#1a56db] hover:text-white transition-all duration-300 group border border-white/5"
                                aria-label="{{ $social['label'] }}" target="_blank" rel="noopener noreferrer">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path d="{{ $social['icon'] }}" />
                                </svg>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Column 2 & 3: Links --}}
            <div class="lg:col-span-2 grid grid-cols-2 gap-10">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-[0.3em] text-[#f5a623] mb-8">
                        {{ __('quick_links') }}
                    </h3>
                    <ul class="space-y-4">
                        @php
                            $links = [
                                ['route' => 'projects.index', 'label' => __('projects')],
                                ['route' => 'tenders.index', 'label' => __('tenders')],
                                ['route' => 'vacancies.index', 'label' => __('vacancies')],
                                ['route' => 'directory.index', 'label' => __('contact_directory')],
                                ['route' => 'contact.index', 'label' => __('feedback')],
                            ];
                        @endphp
                        @foreach($links as $link)
                            <li>
                                <a href="{{ route($link['route']) }}"
                                    class="text-sm font-bold text-gray-400 hover:text-white hover:translate-x-1 transition-all inline-block">
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="text-xs font-black uppercase tracking-[0.3em] text-[#f5a623] mb-8">{{ __('contact_us') }}
                    </h3>
                    <ul class="space-y-5">
                        <li class="flex items-start gap-4 group">
                            <div
                                class="w-8 h-8 rounded-xl bg-white/5 border border-white/5 flex items-center justify-center text-gray-400 group-hover:bg-[#1a56db] group-hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span
                                class="text-sm text-gray-400 font-bold max-w-[200px]">{{ $settings->address ?? 'Kemise, Amhara, Ethiopia' }}</span>
                        </li>
                        <li class="flex items-center gap-4 group">
                            <div
                                class="w-8 h-8 rounded-xl bg-white/5 border border-white/5 flex items-center justify-center text-gray-400 group-hover:bg-[#1a56db] group-hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <span class="text-sm text-gray-400 font-bold truncate">{{ $settings->email ??
                                'info@oromospecialzone.gov.et' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Column 4: Newsletter --}}
            <div>
                <h3 class="text-xs font-black uppercase tracking-[0.3em] text-[#f5a623] mb-8">
                    {{ __('subscribe_updates') }}
                </h3>
                <p class="text-gray-500 text-sm leading-relaxed font-medium mb-8">{{ __('footer_newsletter_desc') }}</p>
                <div class="relative group">
                    <input type="email" placeholder="{{ __('enter_email') }}"
                        class="w-full bg-white/5 border-2 border-white/5 text-white pl-4 pr-16 py-4 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#1a56db] focus:border-[#1a56db] outline-none transition-all placeholder:text-gray-600 shadow-inner">
                    <button
                        class="absolute right-2 top-2 bottom-2 px-6 bg-[#f5a623] text-blue-900 rounded-xl hover:bg-yellow-400 transition-all transform active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Bottom Copyright --}}
        <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-600">
                &copy; {{ date('Y') }} {{ __('title_main') }} {{ __('title_sub') }}. {{ __('all_rights') }}.
            </div>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-black uppercase tracking-[.2em] text-gray-700">{{ __('powered_by') }}</span>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#1a56db]">AgriStack OS</span>
            </div>
        </div>
    </div>
</footer>