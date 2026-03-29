<div x-data="{ view: 'grid' }">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10 pb-6 border-b border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Visual Archive</h2>
            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.3em] mt-1">{{ $items->total() }} Managed Assets</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex bg-gray-100 p-1 rounded-xl mr-2">
                <button @click="view = 'grid'" :class="view === 'grid' ? 'bg-white shadow text-gray-900' : 'text-gray-400'" class="p-2 rounded-lg transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 012 2H4a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 012 2h-8a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 012 2H4a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 012 2h-8a2 2 0 01-2-2v-2z" /></svg></button>
                <button @click="view = 'table'" :class="view === 'table' ? 'bg-white shadow text-gray-900' : 'text-gray-400'" class="p-2 rounded-lg transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg></button>
            </div>
            <button wire:click="openCreate"
                class="flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/20 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>Add New Asset
            </button>
        </div>
    </div>

    <div class="mb-8 flex flex-wrap items-center gap-4">
        <div class="relative min-w-[240px]">
            <select wire:model.live="filterWoreda"
                class="w-full pl-12 pr-6 py-4 bg-white border-2 border-gray-50 rounded-2xl text-xs font-black uppercase tracking-widest text-gray-900 focus:border-emerald-600 transition-all appearance-none cursor-pointer">
                <option value="">All Woredas (Zone Wide)</option>
                @foreach($woredas as $w)<option value="{{ $w->id }}">{{ $w->name_en }}</option>@endforeach
            </select>
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
            </div>
        </div>
    </div>

    <div x-show="view === 'grid'" x-cloak>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
            @forelse($items as $item)
                <div wire:key="gallery-grid-{{ $item->id }}" class="bg-white rounded-[2rem] border-2 border-gray-50 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <div class="aspect-[4/5] relative overflow-hidden">
                        <img src="{{ asset($item->image_url) }}" alt="{{ $item->title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        
                        {{-- Overlay Actions --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                            <div class="flex gap-2 w-full">
                                <button wire:click="openEdit({{ $item->id }})"
                                    class="flex-1 bg-white/20 backdrop-blur-md text-white border border-white/30 rounded-xl py-2 text-[10px] font-black uppercase tracking-widest hover:bg-white hover:text-gray-900 transition">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $item->id }})" wire:confirm="Delete this visual asset?"
                                    class="p-2 bg-red-500/20 backdrop-blur-md text-red-500 border border-red-500/30 rounded-xl hover:bg-red-500 hover:text-white transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-[10px] font-black text-gray-900 uppercase truncate mb-1">{{ $item->title ?: 'Untitled Asset' }}</p>
                        <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">{{ $item->category ?: 'General' }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-sm font-bold text-gray-400">No visual assets found in this archive.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Table View --}}
    <div x-show="view === 'table'" x-cloak class="bg-white rounded-[2rem] border-2 border-gray-50 overflow-hidden shadow-sm">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Preview</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Title (EN)</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Category</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Visibility</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($items as $item)
                    <tr wire:key="gallery-table-{{ $item->id }}" class="hover:bg-emerald-50/10 transition">
                        <td class="px-8 py-4">
                            <img src="{{ asset($item->image_url) }}" class="w-12 h-12 rounded-xl object-cover shadow-sm">
                        </td>
                        <td class="px-8 py-4">
                            <span class="text-xs font-bold text-gray-900">{{ $item->title }}</span>
                        </td>
                        <td class="px-8 py-4">
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">{{ $item->category }}</span>
                        </td>
                        <td class="px-8 py-4">
                            <span class="px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $item->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-400' }}">
                                {{ $item->is_active ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="openEdit({{ $item->id }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
                                <button wire:click="delete({{ $item->id }})" wire:confirm="Delete permanent?" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-8 py-20 text-center text-xs font-bold text-gray-400 tracking-widest uppercase">No assets available</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $items->links() }}</div>

    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col animate-modal-up"
                    x-data="{ tab: 'en' }">
                <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between bg-emerald-50/10">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ $editingId ? 'Edit' : 'Ingest' }} Visual Asset</h3>
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.3em] mt-1">Digital Archive Node</p>
                    </div>
                    <button wire:click="$set('showModal', false)" 
                        class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-10 space-y-8 overflow-y-auto">
                    {{-- Multimedia Preview & Upload --}}
                    <div class="grid grid-cols-2 gap-8">
                        <div class="col-span-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-4">Select Source Image</label>
                            <label class="relative flex flex-col items-center justify-center p-10 bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl hover:border-emerald-600 hover:bg-emerald-50/10 transition group cursor-pointer overflow-hidden">
                                <input type="file" wire:model="image" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                
                                @if($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                                @elseif($editingId && isset($items->find($editingId)->image_url))
                                     <img src="{{ asset($items->find($editingId)->image_url) }}" class="absolute inset-0 w-full h-full object-cover opacity-60">
                                @endif

                                <div class="relative z-0 text-center">
                                    <svg class="w-10 h-10 text-emerald-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Change Visual</span>
                                </div>
                            </label>
                            @error('image') <p class="text-red-500 text-[10px] font-black mt-3 uppercase tracking-tighter">{{ $message }}</p> @enderror
                        </div>

                        {{-- Multilingual Tabs --}}
                        <div class="col-span-2 space-y-6">
                            <div class="flex gap-2 p-1.5 bg-gray-100 rounded-2xl w-fit">
                                <button @click="tab = 'en'" :class="tab === 'en' ? 'bg-white shadow text-emerald-600' : 'text-gray-400'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">English</button>
                                <button @click="tab = 'am'" :class="tab === 'am' ? 'bg-white shadow text-emerald-600' : 'text-gray-400'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">አማርኛ</button>
                                <button @click="tab = 'or'" :class="tab === 'or' ? 'bg-white shadow text-emerald-600' : 'text-gray-400'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Afaan Oromoo</button>
                            </div>

                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Photo Caption</label>
                                <div x-show="tab === 'en'">
                                    <input wire:model="title_en" placeholder="Enter English caption..." 
                                        class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm transition-all text-gray-900">
                                </div>
                                <div x-show="tab === 'am'">
                                    <input wire:model="title_am" placeholder="የምስሉ ገላጭ ጽሑፍ..." 
                                        class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm transition-all text-gray-900">
                                </div>
                                <div x-show="tab === 'or'">
                                    <input wire:model="title_or" placeholder="Ibsa Suuraa..." 
                                        class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm transition-all text-gray-900">
                                </div>
                                @error('title_en') <p class="text-red-500 text-[10px] font-black px-4">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="col-span-1 space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block px-4">Parent Album</label>
                            <select wire:model="album_id" 
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm transition-all text-gray-900 appearance-none">
                                <option value="">No Album (Isolated)</option>
                                @foreach($albums as $album)<option value="{{ $album->id }}">{{ $album->title_en }}</option>@endforeach
                            </select>
                        </div>

                        <div class="col-span-1 space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block px-4">Linked Woreda</label>
                            <select wire:model="woreda_id" 
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm transition-all text-gray-900 appearance-none">
                                <option value="">Zone Level</option>
                                @foreach($woredas as $w)<option value="{{ $w->id }}">{{ $w->name_en }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="px-10 py-10 bg-gray-50/50 border-t border-gray-50 flex items-center justify-end gap-4">
                    <button wire:click="$set('showModal', false)" class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-red-500 transition">Discard</button>
                    <button wire:click="save" class="px-12 py-4 bg-emerald-600 text-white rounded-full text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-emerald-500/20 active:scale-95 transition-all">
                        {{ $editingId ? 'Push Changes' : 'Publish to Gallery' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
</div>