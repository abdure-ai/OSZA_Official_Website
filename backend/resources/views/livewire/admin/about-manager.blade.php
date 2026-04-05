<div>
    <div class="mb-8">
        <h2
            class="text-2xl font-black text-gray-900 border-b-4 border-blue-600 inline-block pb-1 uppercase tracking-tighter">
            About Page Management</h2>
        <p class="text-gray-500 mt-2 font-medium">Customize the History, Mission, Vision, and Strategic Objectives of
            the zone.</p>
    </div>

    {{-- Tabs --}}
    <div class="flex flex-wrap gap-2 mb-8 bg-gray-50 p-1.5 rounded-2xl border border-gray-100">
        <button wire:click="setTab('history')"
            class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'history' ? 'bg-white text-blue-700 shadow-sm border border-gray-100' : 'text-gray-500 hover:text-gray-700' }}">
            Historical Background
        </button>
        <button wire:click="setTab('mission')"
            class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'mission' ? 'bg-white text-blue-700 shadow-sm border border-gray-100' : 'text-gray-500 hover:text-gray-700' }}">
            Our Mission
        </button>
        <button wire:click="setTab('vision')"
            class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'vision' ? 'bg-white text-blue-700 shadow-sm border border-gray-100' : 'text-gray-500 hover:text-gray-700' }}">
            Our Vision
        </button>
        <button wire:click="setTab('objectives')"
            class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'objectives' ? 'bg-white text-blue-700 shadow-sm border border-gray-100' : 'text-gray-500 hover:text-gray-700' }}">
            Strategic Objectives
        </button>
    </div>

    {{-- Content Area --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden p-8">
        @if(in_array($activeTab, ['history', 'mission', 'vision']))
            <form wire:submit="save" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-6 md:col-span-2">
                        <div x-data="{ lang: 'en' }">
                            <div class="flex gap-4 border-b border-gray-100 mb-6">
                                <button type="button" @click="lang = 'en'"
                                    :class="lang === 'en' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-400'"
                                    class="pb-2 border-b-2 text-xs font-black uppercase tracking-widest transition">English</button>
                                <button type="button" @click="lang = 'am'"
                                    :class="lang === 'am' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-400'"
                                    class="pb-2 border-b-2 text-xs font-black uppercase tracking-widest transition">Amharic</button>
                                <button type="button" @click="lang = 'or'"
                                    :class="lang === 'or' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-400'"
                                    class="pb-2 border-b-2 text-xs font-black uppercase tracking-widest transition">Oromo</button>
                            </div>

                            <div x-show="lang === 'en'" class="space-y-6">
                                <div>
                                    <label
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Title
                                        (EN)</label>
                                    <input wire:model="title_en"
                                        class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-600">
                                </div>
                                <div>
                                    <label
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Content
                                        (EN)</label>
                                    <textarea wire:model="content_en" rows="8"
                                        class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-600"></textarea>
                                </div>
                            </div>

                            <div x-show="lang === 'am'" class="space-y-6" style="display:none">
                                <div>
                                    <label
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Title
                                        (AM)</label>
                                    <input wire:model="title_am"
                                        class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-600">
                                </div>
                                <div>
                                    <label
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Content
                                        (AM)</label>
                                    <textarea wire:model="content_am" rows="8"
                                        class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-600"></textarea>
                                </div>
                            </div>

                            <div x-show="lang === 'or'" class="space-y-6" style="display:none">
                                <div>
                                    <label
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Title
                                        (OR)</label>
                                    <input wire:model="title_or"
                                        class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-600">
                                </div>
                                <div>
                                    <label
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Content
                                        (OR)</label>
                                    <textarea wire:model="content_or" rows="8"
                                        class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-600"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($activeTab === 'history')
                    <div class="space-y-8">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-4">Historical Background Image</label>
                            @if($image || $image_url)
                                <div class="relative group rounded-3xl overflow-hidden shadow-xl mb-4 h-48 border-4 border-white">
                                    <img src="{{ $image ? $image->temporaryUrl() : asset($image_url) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <label class="cursor-pointer bg-white text-gray-900 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest">Change Image</label>
                                    </div>
                                    <input type="file" wire:model="image" class="hidden">
                                </div>
                            @else
                                <label class="flex flex-col items-center justify-center w-full h-48 bg-blue-50/50 rounded-3xl border-4 border-dashed border-blue-100 cursor-pointer hover:bg-blue-50 transition group">
                                    <svg class="w-12 h-12 text-blue-200 group-hover:scale-110 transition duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span class="mt-4 text-[10px] font-black text-blue-300 uppercase tracking-widest">Upload Image</span>
                                    <input type="file" wire:model="image" class="hidden">
                                </label>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="space-y-4 {{ $activeTab === 'history' ? '' : 'pt-4' }}">
                        <div class="flex items-center justify-between bg-blue-50 p-6 rounded-3xl">
                            <span class="text-sm font-bold text-blue-900 uppercase tracking-tighter">Status</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-8 border-t border-gray-100">
                    <button type="submit"
                        class="bg-blue-600 text-white px-10 py-4 rounded-2xl text-sm font-black uppercase tracking-widest hover:bg-blue-700 transition shadow-xl shadow-blue-100 flex items-center gap-3">
                        <span wire:loading.remove>Save Changes</span>
                        <span wire:loading>Processing...</span>
                    </button>
                </div>
            </form>
        @else
            {{-- Objectives List --}}
            <div class="space-y-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                        <span class="w-2 h-8 bg-orange-500 rounded-full"></span>
                        Strategic Objectives Grid
                    </h3>
                    <button wire:click="openCreateObjective"
                        class="px-6 py-2.5 bg-gray-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-black transition">
                        Add New Objective
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($objectives as $obj)
                        <div
                            class="bg-gray-50 rounded-3xl p-6 border border-transparent hover:border-blue-100 hover:bg-white hover:shadow-xl transition-all duration-300 group relative">
                            <div class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition">
                                <button wire:click="editObjective({{ $obj->id }})"
                                    class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition"><svg
                                        class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg></button>
                                <button wire:click="delete({{ $obj->id }})" wire:confirm="Delete this objective?"
                                    class="p-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition"><svg
                                        class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg></button>
                            </div>
                            <div class="flex items-start gap-4">
                                <span
                                    class="text-2xl font-black text-gray-200 group-hover:text-blue-100 transition">0{{ $loop->iteration }}</span>
                                <div>
                                    <h4
                                        class="font-black text-gray-900 group-hover:text-blue-700 transition uppercase tracking-tighter">
                                        {{ $obj->title_en }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 py-12 text-center text-gray-400 font-medium">No objectives found.</div>
                    @endforelse
                </div>
                <div class="mt-6">{{ $objectives->links() }}</div>
            </div>
        @endif
    </div>

    {{-- Modal for Objectives --}}
    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto"
                x-data="{ lang: 'en' }">
                <div
                    class="px-10 py-8 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white/80 backdrop-blur z-10">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter">
                            {{ $editingId ? 'Edit' : 'Create' }} Strategic Objective
                        </h3>
                        <div class="flex gap-4 mt-4">
                            <button @click="lang = 'en'"
                                :class="lang === 'en' ? 'text-blue-600 bg-blue-50' : 'text-gray-400'"
                                class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest transition">English</button>
                            <button @click="lang = 'am'"
                                :class="lang === 'am' ? 'text-blue-600 bg-blue-50' : 'text-gray-400'"
                                class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest transition">Amharic</button>
                            <button @click="lang = 'or'"
                                :class="lang === 'or' ? 'text-blue-600 bg-blue-50' : 'text-gray-400'"
                                class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest transition">Oromo</button>
                        </div>
                    </div>
                    <button wire:click="$set('showModal', false)"
                        class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 hover:text-gray-900 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-10 space-y-8">
                    <div x-show="lang === 'en'" class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Objective Title (EN)</label>
                            <input wire:model="title_en" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-600">
                            @error('title_en') <span class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div x-show="lang === 'am'" class="space-y-6" style="display:none">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Objective Title (AM)</label>
                            <input wire:model="title_am" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-600">
                            @error('title_am') <span class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div x-show="lang === 'or'" class="space-y-6" style="display:none">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Objective Title (OR)</label>
                            <input wire:model="title_or" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-600">
                            @error('title_or') <span class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Sort
                                Order</label>
                            <input wire:model="sort_order" type="number"
                                class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-600">
                        </div>
                        <div class="flex items-end bg-blue-50 p-3 px-6 rounded-2xl">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="is_active"
                                    class="w-5 h-5 text-blue-600 rounded-lg border-none focus:ring-blue-600 bg-white">
                                <span class="text-sm font-bold text-blue-900 uppercase tracking-tighter">Active Entry</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div
                    class="px-10 py-8 border-t border-gray-100 flex justify-end items-center gap-4 sticky bottom-0 bg-white">
                    @if (session()->has('message'))
                        <div class="text-green-600 font-bold text-xs uppercase tracking-widest animate-pulse">
                            {{ session('message') }}
                        </div>
                    @endif
                    <button wire:click="$set('showModal', false)"
                        class="px-8 py-3 text-gray-500 font-bold uppercase tracking-widest text-[10px] hover:text-gray-900 transition">Cancel</button>
                    <button wire:click="save"
                        class="bg-blue-600 text-white px-10 py-4 rounded-2xl text-sm font-black uppercase tracking-widest hover:bg-blue-700 transition shadow-xl shadow-blue-100 flex items-center gap-2">
                        <span wire:loading.remove
                            wire:target="save">{{ $editingId ? 'Update Objective' : 'Create Objective' }}</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>