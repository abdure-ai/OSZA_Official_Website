<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Service Sectors</h2>
            <p class="text-sm text-gray-500 font-medium">Manage global public service categories</p>
        </div>
        <button wire:click="openCreate"
            class="flex items-center gap-2 px-6 py-3 bg-[#1a56db] text-white rounded-2xl text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>Add New Sector
        </button>
    </div>



    <div class="mb-6 flex items-center gap-4">
        <div class="relative flex-1 max-w-md">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search sectors..."
                class="w-full pl-12 pr-4 py-3 bg-white border-2 border-gray-100 rounded-2xl text-sm font-medium focus:border-blue-500 focus:outline-none transition-all shadow-sm">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($sectors as $s)
            <div
                class="bg-white rounded-[2rem] border-2 border-gray-50 shadow-sm hover:shadow-xl hover:shadow-blue-900/5 transition-all group p-6 relative overflow-hidden flex flex-col">
                <div class="flex items-start justify-between relative z-10 mb-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 border-2 border-white shadow-inner flex items-center justify-center overflow-hidden">
                            @if($s->icon_svg)
                                {!! $s->icon_svg !!}
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-black text-gray-900 leading-tight">{{ $s->name_en }}</h4>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Order:
                                {{ $s->sort_order }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <span
                            class="w-2 h-2 rounded-full {{ $s->is_active ? 'bg-green-500 animate-pulse' : 'bg-gray-300' }}"></span>
                    </div>
                </div>

                <p class="text-xs text-gray-500 line-clamp-2 flex-1">{{ $s->description_en ?: 'No description provided.' }}
                </p>

                <div class="mt-6 pt-6 border-t border-gray-50 flex items-center justify-between gap-3">
                    <button wire:click="openEdit({{ $s->id }})"
                        class="flex-1 py-2.5 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition">Edit</button>
                    <button wire:click="delete({{ $s->id }})" wire:confirm="Delete this sector?"
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
                <h3 class="font-black text-gray-900 uppercase tracking-widest text-sm">No Sectors Found</h3>
                <p class="text-gray-400 text-xs mt-2">Start by adding your first service sector.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $sectors->links() }}</div>

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
                            Sector</h3>
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
                        class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] border-b-2 transition-all">Config</button>
                </div>

                <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">

                    <!-- Language Tabs Content -->
                    <div x-show="tab === 'en' || tab === 'am' || tab === 'or'" class="space-y-6">
                        <div>
                            <div x-show="tab === 'en'">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Sector
                                    Name (EN) *</label>
                                <input wire:model="name_en"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 font-bold transition-all text-gray-900">
                                @error('name_en') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}
                                </p> @enderror
                            </div>
                            <div x-show="tab === 'am'">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Sector
                                    Name (AM)</label>
                                <input wire:model="name_am"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 font-bold transition-all text-gray-900">
                            </div>
                            <div x-show="tab === 'or'">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Sector
                                    Name (OR)</label>
                                <input wire:model="name_or"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 font-bold transition-all text-gray-900">
                            </div>
                        </div>

                        <div>
                            <label
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Description</label>
                            <textarea x-show="tab === 'en'" wire:model="description_en" rows="5"
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 text-sm transition-all resize-none"></textarea>
                            <textarea x-show="tab === 'am'" wire:model="description_am" rows="5"
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 text-sm transition-all resize-none"></textarea>
                            <textarea x-show="tab === 'or'" wire:model="description_or" rows="5"
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3 text-sm transition-all resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Config Tab Content -->
                    <div x-show="tab === 'config'" class="space-y-8 animate-fade-in">
                        <div class="grid grid-cols-2 gap-8">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">SVG
                                    Icon Code</label>
                                <textarea wire:model="icon_svg" rows="6"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 rounded-2xl px-5 py-3 font-mono text-xs text-gray-600 resize-none"
                                    placeholder="<svg>...</svg>"></textarea>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Sort
                                        Order</label>
                                    <input type="number" wire:model="sort_order"
                                        class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 rounded-2xl px-4 py-3 font-bold">
                                </div>
                                <div class="bg-gray-50 rounded-3xl p-6 relative flex items-center gap-4">
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
                                            class="text-xs font-black uppercase tracking-widest text-gray-400 peer-checked:text-blue-600">Active
                                            Status</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-50 px-8 py-8 flex justify-end gap-3 bg-gray-50/30">
                    <button wire:click="$set('showModal', false)"
                        class="px-8 py-3 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 transition">Discard</button>
                    <button wire:click="save"
                        class="px-12 py-4 bg-blue-600 text-white rounded-full text-xs font-black uppercase tracking-[0.2em] hover:bg-blue-700 transition shadow-xl shadow-blue-500/30 active:scale-95">
                        {{ $editingId ? 'Save Changes' : 'Create Sector' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>