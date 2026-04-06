<div>
        <div class="mb-8">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">System Configuration</h2>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mt-1">Manage global public
                        attributes</p>
        </div>



        <div class="grid xl:grid-cols-2 gap-8">
                {{-- Branding & Appearance --}}
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/40 p-8 col-span-full">
                        <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-[#1a56db]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                </div>
                                Branding & Appearance
                        </h3>
                        <div class="grid md:grid-cols-2 gap-8">
                                {{-- Header Logo --}}
                                <div class="group">
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 group-focus-within:text-[#1a56db] transition-colors">Header Logo</label>
                                        <div class="flex items-center gap-6 p-4 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 group-hover:border-blue-400 transition-all">
                                                <div class="w-20 h-20 bg-white rounded-xl flex items-center justify-center border border-gray-100 shadow-sm overflow-hidden">
                                                        @if ($header_logo && !is_string($header_logo))
                                                                <img src="{{ $header_logo->temporaryUrl() }}" class="max-w-full max-h-full object-contain">
                                                        @elseif ($current_header_logo)
                                                                <img src="{{ asset('storage/' . $current_header_logo) }}" class="max-w-full max-h-full object-contain">
                                                        @else
                                                                <span class="text-gray-300 text-xs">No Logo</span>
                                                        @endif
                                                </div>
                                                <div class="flex-1">
                                                        <input type="file" wire:model="header_logo" class="hidden" id="header_logo_input">
                                                        <label for="header_logo_input" class="bg-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 border border-gray-200 shadow-sm hover:bg-gray-50 cursor-pointer transition-all inline-block">
                                                                Choose Header Logo
                                                        </label>
                                                        <p class="text-[9px] text-gray-400 mt-2 font-medium">PNG, JPG or SVG (Max 2MB)</p>
                                                        <div wire:loading wire:target="header_logo" class="text-xs text-blue-600 font-bold mt-1">Uploading...</div>
                                                </div>
                                        </div>
                                        @error('header_logo') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- Footer Logo --}}
                                <div class="group">
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 group-focus-within:text-[#1a56db] transition-colors">Footer Logo</label>
                                        <div class="flex items-center gap-6 p-4 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 group-hover:border-blue-400 transition-all">
                                                <div class="w-20 h-20 bg-gray-900 rounded-xl flex items-center justify-center border border-gray-800 shadow-sm overflow-hidden">
                                                        @if ($footer_logo && !is_string($footer_logo))
                                                                <img src="{{ $footer_logo->temporaryUrl() }}" class="max-w-full max-h-full object-contain">
                                                        @elseif ($current_footer_logo)
                                                                <img src="{{ asset('storage/' . $current_footer_logo) }}" class="max-w-full max-h-full object-contain">
                                                        @else
                                                                <span class="text-gray-700 text-xs">No Logo</span>
                                                        @endif
                                                </div>
                                                <div class="flex-1">
                                                        <input type="file" wire:model="footer_logo" class="hidden" id="footer_logo_input">
                                                        <label for="footer_logo_input" class="bg-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 border border-gray-200 shadow-sm hover:bg-gray-50 cursor-pointer transition-all inline-block">
                                                                Choose Footer Logo
                                                        </label>
                                                        <p class="text-[9px] text-gray-400 mt-2 font-medium">Best on dark background (Max 2MB)</p>
                                                        <div wire:loading wire:target="footer_logo" class="text-xs text-blue-600 font-bold mt-1">Uploading...</div>
                                                </div>
                                        </div>
                                        @error('footer_logo') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                        </div>
                </div>

                {{-- Contact Details --}}
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/40 p-8">
                        <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-3">
                                <div
                                        class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-[#1a56db]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                </div>
                                Basic Contact
                        </h3>
                        <div class="space-y-5">
                                <div class="group">
                                        <label
                                                class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 group-focus-within:text-[#1a56db] transition-colors">Official
                                                Phone Number</label>
                                        <input wire:model="phone" type="text"
                                                class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all outline-none font-semibold text-gray-900 shadow-inner"
                                                placeholder="+251 33 ...">
                                        @error('phone') <span
                                                class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="group">
                                        <label
                                                class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 group-focus-within:text-[#1a56db] transition-colors">Official
                                                Email</label>
                                        <input wire:model="email" type="email"
                                                class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all outline-none font-semibold text-gray-900 shadow-inner"
                                                placeholder="info@oromospecialzone.gov.et">
                                        @error('email') <span
                                                class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                        @enderror
                                </div>
                        </div>
                </div>

                {{-- Map URL & Social Links --}}
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/40 p-8">
                        <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-3">
                                <div
                                        class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-[#1a56db]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                </div>
                                Map & Social Architecture
                        </h3>
                        <div class="space-y-5">
                                <div class="group">
                                        <label
                                                class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 group-focus-within:text-[#1a56db] transition-colors">Google
                                                Maps Iframe URL</label>
                                        <textarea wire:model="map_url" rows="2"
                                                class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all outline-none font-semibold text-gray-900 shadow-inner text-xs"
                                                placeholder="<iframe src='...'></iframe>"></textarea>
                                        @error('map_url') <span
                                                class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                        <div class="group">
                                                <label
                                                        class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 group-focus-within:text-[#1a56db] transition-colors">Facebook</label>
                                                <input wire:model="facebook_url" type="url"
                                                        class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all outline-none font-semibold text-gray-900 shadow-inner"
                                                        placeholder="https://">
                                                @error('facebook_url') <span
                                                        class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                                @enderror
                                        </div>
                                        <div class="group">
                                                <label
                                                        class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 group-focus-within:text-[#1a56db] transition-colors">Twitter
                                                        (X)</label>
                                                <input wire:model="twitter_url" type="url"
                                                        class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all outline-none font-semibold text-gray-900 shadow-inner"
                                                        placeholder="https://">
                                                @error('twitter_url') <span
                                                        class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                                @enderror
                                        </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                        <div class="group">
                                                <label
                                                        class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 group-focus-within:text-[#1a56db] transition-colors">LinkedIn</label>
                                                <input wire:model="linkedin_url" type="url"
                                                        class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all outline-none font-semibold text-gray-900 shadow-inner"
                                                        placeholder="https://">
                                                @error('linkedin_url') <span
                                                        class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                                @enderror
                                        </div>
                                        <div class="group">
                                                <label
                                                        class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 group-focus-within:text-[#1a56db] transition-colors">YouTube</label>
                                                <input wire:model="youtube_url" type="url"
                                                        class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all outline-none font-semibold text-gray-900 shadow-inner"
                                                        placeholder="https://">
                                                @error('youtube_url') <span
                                                        class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                                @enderror
                                        </div>
                                </div>
                        </div>
                </div>

                {{-- Address Locations --}}
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/40 p-8">
                        <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-3">
                                <div
                                        class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-[#1a56db]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                </div>
                                Headquarters Address
                        </h3>
                        <div class="space-y-5">
                                <div class="group">
                                        <label
                                                class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 flex justify-between group-focus-within:text-[#1a56db] transition-colors">
                                                <span>Address (English)</span> <span class="text-blue-500">EN</span>
                                        </label>
                                        <textarea wire:model="address" rows="2"
                                                class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all outline-none font-semibold text-gray-900 shadow-inner"></textarea>
                                        @error('address') <span
                                                class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="group">
                                        <label
                                                class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 flex justify-between group-focus-within:text-[#1a56db] transition-colors">
                                                <span>Address (Amharic)</span> <span class="text-[#f5a623]">AM</span>
                                        </label>
                                        <textarea wire:model="address_am" rows="2"
                                                class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all outline-none font-semibold text-gray-900 shadow-inner font-amharic"></textarea>
                                        @error('address_am') <span
                                                class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="group">
                                        <label
                                                class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 flex justify-between group-focus-within:text-[#1a56db] transition-colors">
                                                <span>Address (Afaan Oromoo)</span> <span
                                                        class="text-green-600">OR</span>
                                        </label>
                                        <textarea wire:model="address_or" rows="2"
                                                class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all outline-none font-semibold text-gray-900 shadow-inner"></textarea>
                                        @error('address_or') <span
                                                class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                        @enderror
                                </div>
                        </div>
                </div>

                {{-- Working Hours --}}
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/40 p-8">
                        <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-3">
                                <div
                                        class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-[#1a56db]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                </div>
                                Working Hours
                        </h3>
                        <div class="space-y-5">
                                <div class="group">
                                        <label
                                                class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 flex justify-between group-focus-within:text-[#1a56db] transition-colors">
                                                <span>Hours (English)</span> <span class="text-blue-500">EN</span>
                                        </label>
                                        <input wire:model="working_hours" type="text"
                                                class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all outline-none font-semibold text-gray-900 shadow-inner"
                                                placeholder="Mon-Fri, 8am-5pm">
                                        @error('working_hours') <span
                                                class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="group">
                                        <label
                                                class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 flex justify-between group-focus-within:text-[#1a56db] transition-colors">
                                                <span>Hours (Amharic)</span> <span class="text-[#f5a623]">AM</span>
                                        </label>
                                        <input wire:model="working_hours_am" type="text"
                                                class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all outline-none font-semibold text-gray-900 shadow-inner font-amharic">
                                        @error('working_hours_am') <span
                                                class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="group">
                                        <label
                                                class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 flex justify-between group-focus-within:text-[#1a56db] transition-colors">
                                                <span>Hours (Afaan Oromoo)</span> <span class="text-green-600">OR</span>
                                        </label>
                                        <input wire:model="working_hours_or" type="text"
                                                class="w-full px-5 py-3.5 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#1a56db] rounded-2xl transition-all outline-none font-semibold text-gray-900 shadow-inner">
                                        @error('working_hours_or') <span
                                                class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                        @enderror
                                </div>
                        </div>
                </div>

        </div>

        {{-- Action Bar --}}
        <div class="mt-8 flex justify-end">
                <button wire:click="save"
                        class="group flex items-center gap-3 px-8 py-4 bg-[#1a56db] text-white rounded-2xl text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-blue-500/20 hover:bg-blue-800 hover:-translate-y-1 hover:shadow-2xl hover:shadow-blue-500/40 transition-all active:scale-95 cursor-pointer">
                        <svg class="w-5 h-5 transition-transform group-hover:rotate-12" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Commit Settings
                </button>
        </div>
</div>