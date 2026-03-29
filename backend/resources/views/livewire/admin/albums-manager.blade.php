<div x-data="{ view: 'grid' }">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10 pb-6 border-b border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Photo Albums</h2>
            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.3em] mt-1">{{ $items->total() }} Organized Collections</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="openCreate"
                class="flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/20 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>Create New Album
            </button>
        </div>
    </div>

    {{-- Album Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($items as $item)
            <div wire:key="album-grid-{{ $item->id }}" class="bg-white rounded-[2.5rem] border-2 border-gray-50 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden group">
                <div class="aspect-video relative overflow-hidden">
                    <img src="{{ asset($item->cover_image_url) }}" alt="{{ $item->title_en }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    
                    {{-- Badge --}}
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-md text-white text-[8px] font-black uppercase tracking-widest rounded-full border border-white/30">
                            {{ $item->category ?: 'General' }}
                        </span>
                    </div>

                    {{-- Overlay Actions --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                        <div class="flex gap-3 w-full">
                            <button wire:click="openEdit({{ $item->id }})"
                                class="flex-1 bg-white text-gray-900 rounded-xl py-3 text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition shadow-xl">
                                Configure
                            </button>
                            <button wire:click="delete({{ $item->id }})" wire:confirm="Delete this collection and all its history?"
                                class="p-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition shadow-xl">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight mb-2 truncate">{{ $item->title_en }}</h4>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest line-clamp-2">{{ $item->description_en ?: 'No description provided.' }}</p>
                    <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                        <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">{{ $item->items_count ?? $item->items()->count() }} Assets</span>
                        <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest">{{ $item->woreda ? $item->woreda->name_en : 'Zone Wide' }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-32 text-center bg-gray-50 rounded-[3rem] border-4 border-dashed border-gray-100">
                <p class="text-sm font-black text-gray-400 uppercase tracking-[0.2em]">No Albums Found</p>
                <p class="text-xs text-gray-300 mt-2 font-bold">Start by creating a new collection.</p>
            </div>
        @endforelse
    </div>
    <div class="mt-10">{{ $items->links() }}</div>

    {{-- Album Modal --}}
    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col animate-modal-up"
                    x-data="{ tab: 'en' }">
                <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between bg-emerald-50/10">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ $editingId ? 'Edit' : 'Create' }} Album</h3>
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.3em] mt-1">Collection Metadata Manager</p>
                    </div>
                    <button wire:click="$set('showModal', false)" 
                        class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-10 space-y-8 overflow-y-auto">
                    {{-- Cover Image --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-4">Album Cover Image</label>
                        <label class="relative flex flex-col items-center justify-center p-12 bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2.5rem] hover:border-emerald-600 hover:bg-emerald-50/10 transition group cursor-pointer overflow-hidden aspect-video">
                            <input type="file" wire:model="cover_image" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                            
                            @if($cover_image)
                                <img src="{{ $cover_image->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                            @elseif($editingId && Album::find($editingId)->cover_image_url)
                                 <img src="{{ asset(Album::find($editingId)->cover_image_url) }}" class="absolute inset-0 w-full h-full object-cover opacity-60">
                            @endif

                            <div class="relative z-0 text-center">
                                <svg class="w-12 h-12 text-emerald-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600 group-hover:scale-110 transition duration-300 inline-block">Upload Master Cover</span>
                            </div>
                        </label>
                        @error('cover_image') <p class="text-red-500 text-[10px] font-black mt-3 px-4 uppercase tracking-tighter">{{ $message }}</p> @enderror
                    </div>

                    {{-- Multilingual Tabs --}}
                    <div class="space-y-6">
                        <div class="flex gap-2 p-1.5 bg-gray-100 rounded-2xl w-fit">
                            <button @click="tab = 'en'" :class="tab === 'en' ? 'bg-white shadow text-emerald-600' : 'text-gray-400'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all text-gray-900 font-black">English</button>
                            <button @click="tab = 'am'" :class="tab === 'am' ? 'bg-white shadow text-emerald-600' : 'text-gray-400'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all text-gray-900 font-black">አማርኛ</button>
                            <button @click="tab = 'or'" :class="tab === 'or' ? 'bg-white shadow text-emerald-600' : 'text-gray-400'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all text-gray-900 font-black">Afaan Oromoo</button>
                        </div>

                        <div class="space-y-6">
                            {{-- Title --}}
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block px-4">Album Title</label>
                                <div x-show="tab === 'en'">
                                    <input wire:model="title_en" placeholder="Enter English title..." class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm transition-all text-gray-900">
                                </div>
                                <div x-show="tab === 'am'">
                                    <input wire:model="title_am" placeholder="የአልበም ስም..." class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm transition-all text-gray-900">
                                </div>
                                <div x-show="tab === 'or'">
                                    <input wire:model="title_or" placeholder="Maqaa Albami..." class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm transition-all text-gray-900">
                                </div>
                                @error('title_en') <p class="text-red-500 text-[10px] font-black px-4">{{ $message }}</p> @enderror
                            </div>

                            {{-- Description --}}
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block px-4">Short Description</label>
                                <div x-show="tab === 'en'">
                                    <textarea wire:model="description_en" placeholder="English description..." 
                                        class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-3xl px-6 py-4 font-bold text-sm transition-all text-gray-900 h-32"></textarea>
                                </div>
                                <div x-show="tab === 'am'">
                                    <textarea wire:model="description_am" placeholder="የአልበሙ ገላጭ ጽሑፍ..." 
                                        class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-3xl px-6 py-4 font-bold text-sm transition-all text-gray-900 h-32"></textarea>
                                </div>
                                <div x-show="tab === 'or'">
                                    <textarea wire:model="description_or" placeholder="Ibsa Albami..." 
                                        class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-3xl px-6 py-4 font-bold text-sm transition-all text-gray-900 h-32"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-4 px-4">Category</label>
                            <input wire:model="category" placeholder="e.g. Infrastructure, Culture" 
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm transition-all text-gray-900">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-4 px-4">Woreda Context</label>
                            <select wire:model="woreda_id" 
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm transition-all text-gray-900 appearance-none">
                                <option value="">Zone Wide (General)</option>
                                @foreach($woredas as $w)<option value="{{ $w->id }}">{{ $w->name_en }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="px-10 py-10 bg-gray-50/50 border-t border-gray-50 flex items-center justify-end gap-6">
                    <button wire:click="$set('showModal', false)" class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-red-500 transition">Discard</button>
                    <button wire:click="save" class="px-12 py-5 bg-emerald-600 text-white rounded-full text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-emerald-500/20 active:scale-95 transition-all">
                        {{ $editingId ? 'Push Updates' : 'Publish Album' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
