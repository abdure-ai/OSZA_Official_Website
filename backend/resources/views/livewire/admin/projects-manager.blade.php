<div x-data="{ view: localStorage.getItem('projects_view') || 'table' }"
    x-init="$watch('view', v => localStorage.setItem('projects_view', v))">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight text-blue-900">Zonal Development Projects
            </h2>
            <p class="text-sm text-gray-500 font-medium tracking-tight">Monitor and calibrate ongoing strategic
                initiatives</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex bg-gray-50 rounded-2xl p-1.5 border border-gray-100">
                <button @click="view = 'table'"
                    :class="view === 'table' ? 'bg-white shadow text-blue-600' : 'text-gray-400 hover:text-gray-600'"
                    class="p-2.5 rounded-xl transition-all" title="Table View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </button>
                <button @click="view = 'grid'"
                    :class="view === 'grid' ? 'bg-white shadow text-blue-600' : 'text-gray-400 hover:text-gray-600'"
                    class="p-2.5 rounded-xl transition-all" title="Grid View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </button>
            </div>
            <button wire:click="openCreate"
                class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>Initiate Project
            </button>
        </div>
    </div>

    <div x-show="view === 'table'" class="mb-6 flex items-center justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search projects archive..."
                class="w-full pl-12 pr-4 py-3 bg-white border-2 border-gray-100 rounded-2xl text-sm font-medium focus:border-blue-500 focus:outline-none transition-all shadow-sm">
        </div>
        <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-xl border border-gray-100">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Active Records:</span>
            <span class="text-xs font-black text-blue-600 tracking-tighter">{{ $projects->total() }}</span>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border-2 border-gray-50 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/50 border-b-2 border-gray-50">
                <tr>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Project Mapping</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Operational Status</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Project Site</th>
                    <th class="text-right px-8 py-5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($projects as $p)
                            <tr class="hover:bg-blue-50/20 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        @if($p->image_url)
                                            <div class="w-12 h-12 rounded-xl overflow-hidden border-2 border-white shadow-sm">
                                                <img src="{{ config('app.url') . $p->image_url }}" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div
                                                class="w-12 h-12 rounded-xl bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center text-gray-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                            {{ $p->title_en }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ 
                                                        $p->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' :
                    ($p->status === 'ongoing' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-gray-50 text-gray-500 border-gray-100') 
                                                    }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-2 text-gray-500 font-bold text-xs tracking-tight">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $p->location_en ?: 'Undeclared Site' }}
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right space-x-2 text-nowrap">
                                    <button wire:click="openEdit({{ $p->id }})"
                                        class="inline-flex items-center justify-center w-10 h-10 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $p->id }})" wire:confirm="Decommission and delete this project?"
                                        class="inline-flex items-center justify-center w-10 h-10 bg-red-50 text-red-500 rounded-xl hover:bg-red-600 hover:text-white transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="text-gray-300 font-black uppercase tracking-widest text-xs">No Strategic
                                Projects Indexed</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-8 py-6 bg-gray-50/30 border-t border-gray-50">{{ $projects->links() }}</div>
    </div>

    {{-- GRID VIEW --}}
    <div x-show="view === 'grid'" x-cloak>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse($projects as $p)
                <div
                    class="group bg-white rounded-3xl overflow-hidden border-2 border-gray-50 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="aspect-video relative overflow-hidden bg-blue-50">
                        @if($p->image_url)
                            <img src="{{ config('app.url') . $p->image_url }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @elseif($p->cover_image_url)
                            <img src="{{ config('app.url') . $p->cover_image_url }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-blue-200">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        @endif
                        <span
                            class="absolute top-3 right-3 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $p->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($p->status === 'ongoing' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500') }}">{{ $p->status }}</span>
                        <div
                            class="absolute inset-0 bg-blue-900/70 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center gap-2">
                            <button wire:click="openEdit({{ $p->id }})"
                                class="w-10 h-10 bg-white text-blue-600 rounded-full flex items-center justify-center hover:bg-blue-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="delete({{ $p->id }})" wire:confirm="Delete this project?"
                                class="w-10 h-10 bg-white text-red-500 rounded-full flex items-center justify-center hover:bg-red-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <div
                            class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors text-sm leading-tight mb-1">
                            {{ $p->title_en }}</div>
                        @if($p->location_en)
                            <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $p->location_en }}
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full text-center py-20 text-gray-300 font-black text-xs uppercase tracking-widest">
                    No Strategic Projects Indexed</div>
            @endforelse
        </div>
        <div class="mt-8">{{ $projects->links() }}</div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div
                class="bg-white rounded-[3rem] shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col animate-modal-up">
                <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">
                            {{ $editingId ? 'Refine' : 'Initialize' }} Initiative
                        </h3>
                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mt-1">Project Command
                            Center</p>
                    </div>
                    <button wire:click="$set('showModal', false)"
                        class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-10 space-y-8 overflow-y-auto">
                    {{-- Language Tabs --}}
                    <div class="flex gap-4 border-b-2 border-gray-100 pb-4">
                        <button wire:click="$set('activeTab', 'en')"
                            class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition {{ $activeTab === 'en' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">English</button>
                        <button wire:click="$set('activeTab', 'am')"
                            class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition {{ $activeTab === 'am' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">አማርኛ</button>
                        <button wire:click="$set('activeTab', 'or')"
                            class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition {{ $activeTab === 'or' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">Afaan Oromo</button>
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        {{-- MULTILINGUAL FIELDS --}}
                        <div class="col-span-2 space-y-6">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Project Title (Strategic Name)</label>
                                @if($activeTab === 'en')
                                    <input wire:model="title_en" placeholder="e.g. Integrated Rural Water Supply Scheme" class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900">
                                @elseif($activeTab === 'am')
                                    <input wire:model="title_am" placeholder="የርዕስ ትርጉም..." class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900">
                                @else
                                    <input wire:model="title_or" placeholder="Mata duree..." class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900">
                                @endif
                                @error('title_en') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Narrative & Impact Statement</label>
                                @if($activeTab === 'en')
                                    <textarea wire:model="description_en" rows="3" placeholder="Describe the project goals and community benefits..." class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-3xl px-6 py-4 text-sm font-medium transition-all resize-none"></textarea>
                                @elseif($activeTab === 'am')
                                    <textarea wire:model="description_am" rows="3" placeholder="የማብራሪያ ትርጉም..." class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-3xl px-6 py-4 text-sm font-medium transition-all resize-none"></textarea>
                                @else
                                    <textarea wire:model="description_or" rows="3" placeholder="Ibsa..." class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-3xl px-6 py-4 text-sm font-medium transition-all resize-none"></textarea>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-8">
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Project Site / Region</label>
                                    @if($activeTab === 'en')
                                        <input wire:model="location_en" placeholder="e.g. Sheger City" class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @elseif($activeTab === 'am')
                                        <input wire:model="location_am" placeholder="የቦታ ትርጉም..." class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @else
                                        <input wire:model="location_or" placeholder="Iddoo..." class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @endif
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Principal Contractor</label>
                                    @if($activeTab === 'en')
                                        <input wire:model="contractor" placeholder="e.g. Oromia Construction Corp." class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @elseif($activeTab === 'am')
                                        <input wire:model="contractor_am" placeholder="የስራ ተቋራጭ..." class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @else
                                        <input wire:model="contractor_or" placeholder="Kontaarkitara..." class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Funding Source / Partners</label>
                                @if($activeTab === 'en')
                                    <input wire:model="funding_source" placeholder="e.g. World Bank / Regional Gov" class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                @elseif($activeTab === 'am')
                                    <input wire:model="funding_source_am" placeholder="የገንዘብ ምንጭ..." class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                @else
                                    <input wire:model="funding_source_or" placeholder="Madda Maallaqaa..." class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                @endif
                            </div>
                        </div>

                        {{-- UNIVERSAL FIELDS --}}
                        <div class="col-span-2 border-t-2 border-gray-100 pt-6 mt-4">
                            <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-6">Master Operational Data</h4>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Target Budget</label>
                                    <input wire:model="budget" placeholder="1,000,000.00" class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @error('budget') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Progress (%)</label>
                                    <input wire:model="progress" type="number" min="0" max="100" placeholder="75" class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @error('progress') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Commencement Date</label>
                                    <input type="date" wire:model="start_date" class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @error('start_date') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Projected Handover</label>
                                    <input type="date" wire:model="end_date" class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @error('end_date') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Operational Status</label>
                                    <select wire:model="status" class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm outline-none appearance-none">
                                        <option value="planned">CONCEPT / PLANNED</option>
                                        <option value="ongoing">OPERATIONAL / ONGOING</option>
                                        <option value="completed">FINALIZED / COMPLETED</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Public Visibility</label>
                                    <select wire:model="is_published" class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm outline-none appearance-none">
                                        <option value="1">PUBLISHED</option>
                                        <option value="0">DRAFT (HIDDEN)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Project
                                Visual Representation</label>
                            <div
                                class="relative flex items-center justify-center p-6 bg-blue-50/50 border-2 border-dashed border-blue-200 rounded-3xl hover:border-blue-400 transition cursor-pointer">
                                <input type="file" wire:model="image" class="absolute inset-0 opacity-0 cursor-pointer">
                                <div
                                    class="text-[10px] font-black text-blue-600 uppercase tracking-widest flex items-center gap-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $image ? 'VISUAL ASSET READY' : 'REPLACE PROJECT IMAGE' }}
                                </div>
                            </div>
                            <div wire:loading wire:target="image"
                                class="text-[9px] text-blue-500 font-bold mt-2 animate-pulse">OPTIMIZING GEOSPATIAL
                                ASSETS...</div>
                        </div>
                    </div>
                </div>

                <div class="px-10 py-10 bg-gray-50/50 border-t border-gray-50 flex items-center justify-end gap-3">
                    <button wire:click="$set('showModal', false)"
                        class="px-8 py-3 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-red-500 transition">Abort
                        Operational Change</button>
                    <button wire:click="save"
                        class="px-12 py-4 bg-blue-600 text-white rounded-full text-xs font-black uppercase tracking-[0.2em] hover:bg-blue-700 transition shadow-xl shadow-blue-500/30 active:scale-95">
                        {{ $editingId ? 'Authorize Updates' : 'Launch Initiative' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>