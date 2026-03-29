<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Woredas</h2>
            <p class="text-sm text-gray-500 font-medium">Manage administrative zones and portals</p>
        </div>
        <button wire:click="openCreate"
            class="flex items-center gap-2 px-6 py-3 bg-[#1a56db] text-white rounded-2xl text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>Add New Woreda
        </button>
    </div>

    <!-- Toast Notification -->
    @if(session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="fixed bottom-10 right-10 z-[100] bg-green-600 text-white px-8 py-4 rounded-2xl shadow-2xl flex items-center gap-4 animate-bounce">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-xl font-bold">✓</div>
            <div>
                <div class="font-black uppercase tracking-tight">Success</div>
                <div class="text-sm opacity-90">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    <div class="mb-6 flex items-center gap-4">
        <div class="relative flex-1 max-w-md">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name or slug..."
                class="w-full pl-12 pr-4 py-3 bg-white border-2 border-gray-100 rounded-2xl text-sm font-medium focus:border-blue-500 focus:outline-none transition-all shadow-sm">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($woredas as $w)
            <div
                class="bg-white rounded-[2rem] border-2 border-gray-50 shadow-sm hover:shadow-xl hover:shadow-blue-900/5 transition-all group p-6 relative overflow-hidden">
                <div class="flex items-start justify-between relative z-10">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-16 h-16 rounded-2xl bg-gray-50 border-2 border-white shadow-inner flex items-center justify-center overflow-hidden">
                            @if($w->logo_url)
                                <img src="{{ asset($w->logo_url) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-2xl font-black text-blue-200">{{ substr($w->name_en, 0, 1) }}</span>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-black text-gray-900 leading-tight">{{ $w->name_en }}</h4>
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mt-0.5">
                                {{ $w->capital_en ?: 'No Capital Set' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <span
                            class="w-2 h-2 rounded-full {{ $w->is_active ? 'bg-green-500 animate-pulse' : 'bg-gray-300' }}"></span>
                        <span
                            class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">{{ $w->is_active ? 'Online' : 'Hidden' }}</span>
                    </div>
                </div>

                <div
                    class="mt-6 flex items-center justify-between text-[10px] font-black uppercase tracking-widest text-gray-400">
                    <div>Pop: <span class="text-gray-900">{{ number_format($w->population) }}</span></div>
                    <div>Slug: <span class="text-blue-500">{{ $w->slug }}</span></div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-50 flex items-center justify-between gap-3">
                    <a href="{{ route('woreda.show', $w->slug) }}" target="_blank"
                        class="flex-1 text-center py-2.5 bg-gray-50 text-gray-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 transition">View
                        Portal</a>
                    <button wire:click="openEdit({{ $w->id }})"
                        class="flex-1 py-2.5 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition">Edit</button>
                    <button wire:click="delete({{ $w->id }})" wire:confirm="Delete this woreda and all its data?"
                        class="px-3 py-2.5 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition group/del">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <div
                class="col-span-full py-20 bg-white rounded-[2rem] border-2 border-dashed border-gray-100 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="font-black text-gray-900 uppercase tracking-widest text-sm">No Woredas Found</h3>
                <p class="text-gray-400 text-xs mt-2">Start by adding your first administrative woreda.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $woredas->links() }}</div>

    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            x-data="{ tab: 'en' }">
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col"
                @click.stop x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="border-b border-gray-50 px-8 py-6 flex items-center justify-between bg-white relative">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 tracking-tight">{{ $editingId ? 'Edit' : 'Create' }}
                            Woreda</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Global Information System
                        </p>
                    </div>
                    <button wire:click="$set('showModal', false)"
                        class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Tabs -->
                <div class="flex border-b border-gray-50 bg-gray-50/50 px-8">
                    <button @click="tab = 'en'"
                        :class="tab === 'en' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-400 hover:text-gray-600'"
                        class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] border-b-2 transition-all">English</button>
                    <button @click="tab = 'am'"
                        :class="tab === 'am' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-400 hover:text-gray-600'"
                        class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] border-b-2 transition-all">አማርኛ</button>
                    <button @click="tab = 'or'"
                        :class="tab === 'or' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-400 hover:text-gray-600'"
                        class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] border-b-2 transition-all">Afaan
                        Oromo</button>
                    <button @click="tab = 'config'"
                        :class="tab === 'config' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-red-400 hover:text-red-600'"
                        class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] border-b-2 transition-all">Config
                        & Media</button>
                    <button @click="tab = 'services'"
                        :class="tab === 'services' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-green-400 hover:text-green-600'"
                        class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] border-b-2 transition-all">Services</button>
                </div>

                <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">

                    <!-- Language Tabs Content -->
                    <div x-show="tab === 'en' || tab === 'am' || tab === 'or'" class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div x-show="tab === 'en'">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Display
                                    Name (EN) *</label>
                                <input wire:model="name_en"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 font-bold transition-all text-gray-900">
                                @error('name_en') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}
                                </p> @enderror
                            </div>
                            <div x-show="tab === 'am'">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Display
                                    Name (AM)</label>
                                <input wire:model="name_am"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 font-bold transition-all text-gray-900">
                            </div>
                            <div x-show="tab === 'or'">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Display
                                    Name (OR)</label>
                                <input wire:model="name_or"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 font-bold transition-all text-gray-900">
                            </div>

                            <div x-show="tab === 'en'">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Capital
                                    City (EN)</label>
                                <input wire:model="capital_en"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 font-bold transition-all text-gray-900">
                            </div>
                            <div x-show="tab === 'am'">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Capital
                                    City (AM)</label>
                                <input wire:model="capital_am"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 font-bold transition-all text-gray-900">
                            </div>
                            <div x-show="tab === 'or'">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Capital
                                    City (OR)</label>
                                <input wire:model="capital_or"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 font-bold transition-all text-gray-900">
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Full
                                Description</label>
                            <textarea x-show="tab === 'en'" wire:model="description_en" rows="3"
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 text-sm transition-all resize-none"></textarea>
                            <textarea x-show="tab === 'am'" wire:model="description_am" rows="3"
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 text-sm transition-all resize-none"></textarea>
                            <textarea x-show="tab === 'or'" wire:model="description_or" rows="3"
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 text-sm transition-all resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Mission</label>
                                <textarea x-show="tab === 'en'" wire:model="mission_en" rows="3"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 text-sm transition-all resize-none"></textarea>
                                <textarea x-show="tab === 'am'" wire:model="mission_am" rows="3"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 text-sm transition-all resize-none"></textarea>
                                <textarea x-show="tab === 'or'" wire:model="mission_or" rows="3"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 text-sm transition-all resize-none"></textarea>
                            </div>
                            <div>
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Vision</label>
                                <textarea x-show="tab === 'en'" wire:model="vision_en" rows="3"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 text-sm transition-all resize-none"></textarea>
                                <textarea x-show="tab === 'am'" wire:model="vision_am" rows="3"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 text-sm transition-all resize-none"></textarea>
                                <textarea x-show="tab === 'or'" wire:model="vision_or" rows="3"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 text-sm transition-all resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Config Tab Content -->
                    <div x-show="tab === 'config'" class="space-y-8 animate-fade-in">
                        <div class="grid grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Portal
                                        Slug (URL)</label>
                                    <input wire:model="slug" placeholder="e.g. adama-special"
                                        class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 rounded-2xl px-5 py-3 font-bold">
                                    @error('slug') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Population</label>
                                        <input type="number" wire:model="population"
                                            class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 rounded-2xl px-4 py-2 text-sm">
                                        @error('population') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Area
                                            (km²)</label>
                                        <input type="number" step="0.01" wire:model="area_km2"
                                            class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 rounded-2xl px-4 py-2 text-sm">
                                        @error('area_km2') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Est.
                                            Year</label>
                                        <input type="number" wire:model="established_year" placeholder="e.g. 1994"
                                            class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 rounded-2xl px-4 py-2 text-sm">
                                        @error('established_year') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 border-2 border-dashed border-gray-100 rounded-[2rem] p-6">
                                <label
                                    class="text-[10px] font-black text-blue-600 uppercase tracking-widest block mb-6 px-2">Administrator
                                    Profile</label>
                                <div class="space-y-4">
                                    <input wire:model="administrator_name" placeholder="Name"
                                        class="w-full bg-white border-2 border-transparent focus:border-blue-500 rounded-xl px-4 py-2 text-sm shadow-sm font-bold">
                                    <input wire:model="administrator_title" placeholder="Professional Title"
                                        class="w-full bg-white border-2 border-transparent focus:border-blue-500 rounded-xl px-4 py-2 text-sm shadow-sm font-medium">

                                    <div class="grid grid-cols-2 gap-3 mt-4">
                                        <label class="cursor-pointer">
                                            <input type="file" wire:model="admin_photo" class="hidden">
                                            <div
                                                class="py-3 px-4 bg-white rounded-xl border-2 border-gray-50 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest hover:border-blue-200 transition shadow-sm">
                                                {{ $admin_photo ? '✓ Changed' : 'Portrait' }}
                                            </div>
                                        </label>
                                        <div wire:loading wire:target="admin_photo"
                                            class="text-[10px] text-blue-500 font-bold animate-pulse">Uploading...</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="bg-blue-50/50 p-6 rounded-3xl border-2 border-blue-50">
                                <label
                                    class="text-[10px] font-black text-blue-400 uppercase tracking-widest block mb-4">Official
                                    Logo</label>
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center overflow-hidden border">
                                        @if($logo) <img src="{{ $logo->temporaryUrl() }}"
                                        class="w-full h-full object-cover"> @else <svg class="w-6 h-6 text-gray-200"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" />
                                            </svg> @endif
                                    </div>
                                    <input type="file" wire:model="logo"
                                        class="text-[10px] font-medium text-gray-500 flex-1">
                                </div>
                            </div>
                            <div class="bg-indigo-50/50 p-6 rounded-3xl border-2 border-indigo-50">
                                <label
                                    class="text-[10px] font-black text-indigo-400 uppercase tracking-widest block mb-4">Banner
                                    Image</label>
                                <input type="file" wire:model="banner" class="text-[10px] font-medium text-gray-500">
                            </div>
                            <div class="flex items-center justify-center bg-gray-50 rounded-3xl p-6">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div
                                        class="relative w-12 h-6 bg-gray-200 rounded-full transition-colors group-hover:bg-gray-300">
                                        <input type="checkbox" wire:model="is_active"
                                            class="absolute inset-0 opacity-0 cursor-pointer peer">
                                        <div
                                            class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all peer-checked:left-7 peer-checked:bg-blue-600 shadow-sm">
                                        </div>
                                    </div>
                                    <span
                                        class="text-xs font-black uppercase tracking-widest text-gray-400 peer-checked:text-blue-600">Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="bg-gray-900 rounded-[2rem] p-8 text-white">
                            <h5
                                class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-400 mb-6 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Contact Registry
                            </h5>
                            <div class="grid grid-cols-2 gap-8">
                                <div>
                                    <label
                                        class="text-[9px] font-black text-gray-500 uppercase tracking-widest block mb-2 px-1">Helpline
                                        Phone</label>
                                    <input wire:model="contact_phone"
                                        class="w-full bg-white/5 border-2 border-transparent focus:border-blue-500 rounded-2xl px-5 py-3 text-sm font-bold text-white transition-all">
                                    @error('contact_phone') <p class="text-red-400 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label
                                        class="text-[9px] font-black text-gray-500 uppercase tracking-widest block mb-2 px-1">Official
                                        Email</label>
                                    <input wire:model="contact_email"
                                        class="w-full bg-white/5 border-2 border-transparent focus:border-blue-500 rounded-2xl px-5 py-3 text-sm font-bold text-white transition-all">
                                    @error('contact_email') <p class="text-red-400 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Services Tab Content -->
                    <div x-show="tab === 'services'" class="space-y-6 animate-fade-in">
                        <div class="space-y-4">
                            @foreach($serviceSectors as $sector)
                                <div class="bg-gray-50 border-2 border-gray-100 rounded-2xl p-6 relative"
                                    x-data="{ slocale: 'en' }">
                                    <label class="flex items-center gap-3 cursor-pointer group mb-4">
                                        <div
                                            class="relative w-12 h-6 bg-gray-200 rounded-full transition-colors group-hover:bg-gray-300">
                                            <input type="checkbox" wire:model="selected_services.{{ $sector->id }}.is_selected"
                                                class="absolute inset-0 opacity-0 cursor-pointer peer">
                                            <div
                                                class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all peer-checked:left-7 peer-checked:bg-blue-600 shadow-sm">
                                            </div>
                                        </div>
                                        <span
                                            class="text-sm font-black text-gray-900 border-b border-gray-200">{{ $sector->name_en }}</span>
                                    </label>

                                    <div x-show="$wire.selected_services[{{ $sector->id }}] && $wire.selected_services[{{ $sector->id }}]['is_selected']"
                                        class="border-t border-gray-200 pt-6 mt-2 animate-fade-in" style="display: none;">
                                        <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-4">Official
                                            Details</h4>

                                        <div class="grid md:grid-cols-12 gap-6">
                                            <!-- Photo Upload -->
                                            <div class="md:col-span-3">
                                                <label
                                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Photo</label>
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-16 h-16 bg-white border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center overflow-hidden shrink-0 shadow-sm">
                                                        @if(isset($selected_services[$sector->id]['new_photo']) && $selected_services[$sector->id]['new_photo'])
                                                            <img src="{{ $selected_services[$sector->id]['new_photo']->temporaryUrl() }}"
                                                                class="w-full h-full object-cover">
                                                        @elseif(isset($selected_services[$sector->id]['official_photo_url']) && $selected_services[$sector->id]['official_photo_url'])
                                                            <img src="{{ $selected_services[$sector->id]['official_photo_url'] }}"
                                                                class="w-full h-full object-cover">
                                                        @else
                                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1">
                                                        <label class="cursor-pointer block">
                                                            <input type="file"
                                                                wire:model="selected_services.{{ $sector->id }}.new_photo"
                                                                class="hidden" accept="image/*">
                                                            <span
                                                                class="text-[10px] font-black bg-white border border-gray-200 rounded-lg px-3 py-1.5 text-gray-600 hover:bg-gray-50 flex justify-center uppercase tracking-widest shadow-sm">Upload</span>
                                                        </label>
                                                        <div wire:loading
                                                            wire:target="selected_services.{{ $sector->id }}.new_photo"
                                                            class="text-[9px] text-blue-500 font-bold mt-1 animate-pulse text-center">
                                                            Uploading...</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Text Details -->
                                            <div class="md:col-span-9 space-y-4">
                                                <div class="flex gap-2 mb-2">
                                                    <button type="button" @click="slocale = 'en'"
                                                        :class="slocale === 'en' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                                        class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg transition">EN</button>
                                                    <button type="button" @click="slocale = 'am'"
                                                        :class="slocale === 'am' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                                        class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg transition">AM</button>
                                                    <button type="button" @click="slocale = 'or'"
                                                        :class="slocale === 'or' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                                        class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg transition">OR</button>
                                                </div>

                                                <div class="grid grid-cols-2 gap-4">
                                                    <div x-show="slocale === 'en'">
                                                        <label
                                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Name
                                                            (EN)</label>
                                                        <input wire:model="selected_services.{{ $sector->id }}.official_name_en"
                                                            placeholder="e.g. John Doe"
                                                            class="w-full bg-white border-2 border-transparent focus:border-blue-500 rounded-xl px-4 py-2 text-sm">
                                                    </div>
                                                    <div x-show="slocale === 'am'" style="display: none;">
                                                        <label
                                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Name
                                                            (AM)</label>
                                                        <input wire:model="selected_services.{{ $sector->id }}.official_name_am"
                                                            placeholder="አማርኛ"
                                                            class="w-full bg-white border-2 border-transparent focus:border-blue-500 rounded-xl px-4 py-2 text-sm">
                                                    </div>
                                                    <div x-show="slocale === 'or'" style="display: none;">
                                                        <label
                                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Name
                                                            (OR)</label>
                                                        <input wire:model="selected_services.{{ $sector->id }}.official_name_or"
                                                            placeholder="Afaan Oromoo"
                                                            class="w-full bg-white border-2 border-transparent focus:border-blue-500 rounded-xl px-4 py-2 text-sm">
                                                    </div>

                                                    <div x-show="slocale === 'en'">
                                                        <label
                                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Title
                                                            (EN)</label>
                                                        <input
                                                            wire:model="selected_services.{{ $sector->id }}.official_title_en"
                                                            placeholder="e.g. Head of Office"
                                                            class="w-full bg-white border-2 border-transparent focus:border-blue-500 rounded-xl px-4 py-2 text-sm">
                                                    </div>
                                                    <div x-show="slocale === 'am'" style="display: none;">
                                                        <label
                                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Title
                                                            (AM)</label>
                                                        <input
                                                            wire:model="selected_services.{{ $sector->id }}.official_title_am"
                                                            class="w-full bg-white border-2 border-transparent focus:border-blue-500 rounded-xl px-4 py-2 text-sm">
                                                    </div>
                                                    <div x-show="slocale === 'or'" style="display: none;">
                                                        <label
                                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Title
                                                            (OR)</label>
                                                        <input
                                                            wire:model="selected_services.{{ $sector->id }}.official_title_or"
                                                            class="w-full bg-white border-2 border-transparent focus:border-blue-500 rounded-xl px-4 py-2 text-sm">
                                                    </div>

                                                    <div class="col-span-1">
                                                        <label
                                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Phone</label>
                                                        <input wire:model="selected_services.{{ $sector->id }}.official_phone"
                                                            placeholder="+251..."
                                                            class="w-full bg-white border-2 border-transparent focus:border-blue-500 rounded-xl px-4 py-2 text-sm">
                                                    </div>
                                                    <div class="col-span-1">
                                                        <label
                                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Email</label>
                                                        <input wire:model="selected_services.{{ $sector->id }}.official_email"
                                                            placeholder="email@example.com"
                                                            class="w-full bg-white border-2 border-transparent focus:border-blue-500 rounded-xl px-4 py-2 text-sm">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-50 px-8 py-8 flex justify-end gap-3 bg-gray-50/30">
                    <button wire:click="$set('showModal', false)"
                        class="px-8 py-3 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 transition">Discard</button>
                    <button wire:click="save"
                        class="px-12 py-4 bg-blue-600 text-white rounded-full text-xs font-black uppercase tracking-[0.2em] hover:bg-blue-700 transition shadow-xl shadow-blue-500/30 active:scale-95">
                        {{ $editingId ? 'Push Updates' : 'Launch Portal' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>