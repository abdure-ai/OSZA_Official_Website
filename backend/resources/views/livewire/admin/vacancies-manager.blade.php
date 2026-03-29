<div x-data="{ view: localStorage.getItem('vacancies_view') || 'table' }"
    x-init="$watch('view', v => localStorage.setItem('vacancies_view', v))">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight text-emerald-900">Career Opportunities
            </h2>
            <p class="text-sm text-gray-500 font-medium tracking-tight">Recruit and manage strategic talent for the Zone
            </p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex bg-gray-50 rounded-2xl p-1.5 border border-gray-100">
                <button @click="view = 'table'"
                    :class="view === 'table' ? 'bg-white shadow text-emerald-600' : 'text-gray-400 hover:text-gray-600'"
                    class="p-2.5 rounded-xl transition-all" title="Table View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </button>
                <button @click="view = 'grid'"
                    :class="view === 'grid' ? 'bg-white shadow text-emerald-600' : 'text-gray-400 hover:text-gray-600'"
                    class="p-2.5 rounded-xl transition-all" title="Grid View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </button>
            </div>
            <button wire:click="openCreate"
                class="flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/20 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>Post New Vacancy
            </button>
        </div>
    </div>

    {{-- TABLE VIEW --}}
    <div x-show="view === 'table'" class="bg-white rounded-[2.5rem] border-2 border-gray-50 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/50 border-b-2 border-gray-50">
                <tr>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Position & Role</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Department</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Status</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Deadline</th>
                    <th class="text-right px-8 py-5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($vacancies as $v)
                    <tr wire:key="vacancy-table-{{ $v->id }}" class="hover:bg-emerald-50/20 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">
                                {{ $v->title_en }}
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span
                                class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                {{ $v->department ?: 'General Admin' }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2">
                                <span
                                    class="w-2 h-2 rounded-full {{ $v->is_active ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-gray-300' }}"></span>
                                <span
                                    class="text-[10px] font-black uppercase tracking-widest {{ $v->is_active ? 'text-emerald-700' : 'text-gray-400' }}">
                                    {{ $v->is_active ? 'Accepting Apps' : 'Paused' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-xs font-bold text-gray-500">
                                {{ $v->deadline ? \Carbon\Carbon::parse($v->deadline)->format('M d, Y') : 'Open Ended' }}
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right space-x-2">
                            <button wire:click="openEdit({{ $v->id }})"
                                class="inline-flex items-center justify-center w-10 h-10 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-sm group/btn">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="delete({{ $v->id }})" wire:confirm="Archive and delete this vacancy?"
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
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="text-gray-300 font-black uppercase tracking-widest text-xs">No Career
                                Listings Active</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-8 py-6 bg-gray-50/30 border-t border-gray-50">{{ $vacancies->links() }}</div>
    </div>

    {{-- GRID VIEW --}}
    <div x-show="view === 'grid'" x-cloak>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($vacancies as $v)
                <div wire:key="vacancy-grid-{{ $v->id }}"
                    class="group bg-white rounded-3xl border-2 border-gray-50 shadow-sm p-7 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col gap-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <h3
                                class="font-bold text-gray-900 group-hover:text-emerald-600 transition-colors leading-tight">
                                {{ $v->title_en }}</h3>
                            <span
                                class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $v->department ?: 'General Admin' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            <span
                                class="w-2 h-2 rounded-full {{ $v->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                            <span
                                class="text-[9px] font-black uppercase tracking-widest {{ $v->is_active ? 'text-emerald-700' : 'text-gray-400' }}">{{ $v->is_active ? 'Active' : 'Paused' }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-auto">
                        <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest">
                            {{ $v->deadline ? \Carbon\Carbon::parse($v->deadline)->format('M d, Y') : 'Open Ended' }}</div>
                        <div class="flex gap-2">
                            <button wire:click="openEdit({{ $v->id }})"
                                class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="delete({{ $v->id }})" wire:confirm="Delete this vacancy?"
                                class="w-9 h-9 bg-red-50 text-red-500 rounded-xl flex items-center justify-center hover:bg-red-600 hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full text-center py-20 text-gray-300 font-black text-xs uppercase tracking-widest">
                    No Career Listings Active</div>
            @endforelse
        </div>
        <div class="mt-8">{{ $vacancies->links() }}</div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div
                class="bg-white rounded-[3rem] shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col animate-modal-up"
                x-data="{ tab: 'en' }">
                <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between bg-emerald-50/10">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">
                            {{ $editingId ? 'Evolve' : 'Create' }} Role
                        </h3>
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.3em] mt-1">HR Management
                            Terminal</p>
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
                    <div class="flex gap-2 p-1.5 bg-gray-50 rounded-2xl w-fit">
                        <button @click="tab = 'en'" :class="tab === 'en' ? 'bg-white shadow text-emerald-600' : 'text-gray-400'"
                            class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">English</button>
                        <button @click="tab = 'am'" :class="tab === 'am' ? 'bg-white shadow text-emerald-600' : 'text-gray-400'"
                            class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">አማርኛ</button>
                        <button @click="tab = 'or'" :class="tab === 'or' ? 'bg-white shadow text-emerald-600' : 'text-gray-400'"
                            class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Afaan Oromoo</button>
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        {{-- Title --}}
                        <div class="col-span-2 space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Position Title</label>
                            <div x-show="tab === 'en'">
                                <input wire:model="title_en" placeholder="e.g. Senior Strategic Planner"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900">
                                @error('title_en') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p> @enderror
                            </div>
                            <div x-show="tab === 'am'">
                                <input wire:model="title_am" placeholder="የሥራ መደብ መጠሪያ..."
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900">
                            </div>
                            <div x-show="tab === 'or'">
                                <input wire:model="title_or" placeholder="Moggaasa Hojii..."
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900">
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="col-span-2 space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Role Responsibilities</label>
                            <div x-show="tab === 'en'">
                                <textarea wire:model="description_en" rows="4"
                                    placeholder="Outline the key duties of this position..."
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-3xl px-6 py-4 text-sm font-medium transition-all resize-none"></textarea>
                            </div>
                            <div x-show="tab === 'am'">
                                <textarea wire:model="description_am" rows="4"
                                    placeholder="የሥራው ኃላፊነቶች..."
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-3xl px-6 py-4 text-sm font-medium transition-all resize-none"></textarea>
                            </div>
                            <div x-show="tab === 'or'">
                                <textarea wire:model="description_or" rows="4"
                                    placeholder="Gahee hojii..."
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-3xl px-6 py-4 text-sm font-medium transition-all resize-none"></textarea>
                            </div>
                        </div>

                        {{-- Requirements --}}
                        <div class="col-span-2 space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Candidate Requirements</label>
                            <div x-show="tab === 'en'">
                                <textarea wire:model="requirements_en" rows="4"
                                    placeholder="Education, Experience, Skills..."
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-3xl px-6 py-4 text-sm font-medium transition-all resize-none"></textarea>
                            </div>
                            <div x-show="tab === 'am'">
                                <textarea wire:model="requirements_am" rows="4"
                                    placeholder="ትምህርት፣ የሥራ ልምድ፣ ክህሎት..."
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-3xl px-6 py-4 text-sm font-medium transition-all resize-none"></textarea>
                            </div>
                            <div x-show="tab === 'or'">
                                <textarea wire:model="requirements_or" rows="4"
                                    placeholder="Barnoota, Muuxannoo, Dandeeettii..."
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-3xl px-6 py-4 text-sm font-medium transition-all resize-none"></textarea>
                            </div>
                        </div>

                        {{-- Location --}}
                        <div class="col-span-2 space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Work Location</label>
                            <div x-show="tab === 'en'">
                                <input wire:model="location_en" placeholder="e.g. Bishoftu Office"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900">
                            </div>
                            <div x-show="tab === 'am'">
                                <input wire:model="location_am" placeholder="የሥራ ቦታ..."
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900">
                            </div>
                            <div x-show="tab === 'or'">
                                <input wire:model="location_or" placeholder="Bakka Hojii..."
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900">
                            </div>
                        </div>

                        {{-- Meta Info --}}
                        <div class="col-span-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Department</label>
                            <input wire:model="department" placeholder="e.g. Finance & Treasury"
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm text-gray-900">
                        </div>

                        <div class="col-span-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Vacancy Type</label>
                            <select wire:model="vacancy_type" 
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm text-gray-900">
                                <option value="">Select Type</option>
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                                <option value="Contract">Contract</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>

                        <div class="col-span-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Application Deadline</label>
                            <input type="date" wire:model="deadline"
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm text-gray-900">
                        </div>

                        <div class="col-span-2 flex items-center justify-between p-6 bg-gray-50 rounded-3xl">
                            <div>
                                <h5 class="text-xs font-black text-gray-900 uppercase tracking-widest">Active Status</h5>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">Toggle visibility on
                                    public portal</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                <div
                                    class="w-14 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-6 after:transition-all peer-checked:bg-emerald-600">
                                </div>
                            </label>
                        </div>

                        <div class="col-span-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Job
                                Description (PDF Upload)</label>
                            <div
                                class="relative flex items-center justify-center p-6 bg-emerald-50/30 border-2 border-dashed border-emerald-200 rounded-3xl hover:border-emerald-400 transition cursor-pointer">
                                <input type="file" wire:model="document" class="absolute inset-0 opacity-0 cursor-pointer">
                                <div
                                    class="text-[10px] font-black text-emerald-600 uppercase tracking-widest flex items-center gap-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ $document ? 'DOCUMENT ATTACHED' : 'UPLOAD ROLE SPECIFICATION' }}
                                </div>
                            </div>
                            <div wire:loading wire:target="document"
                                class="text-[9px] text-emerald-500 font-bold mt-2 animate-pulse">OPTIMIZING ASSETS...</div>
                        </div>
                    </div>
                </div>

                <div class="px-10 py-10 bg-gray-50/50 border-t border-gray-50 flex items-center justify-end gap-3">
                    <button wire:click="$set('showModal', false)"
                        class="px-8 py-3 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-red-500 transition">Discard
                        Draft</button>
                    <button wire:click="save"
                        class="px-12 py-4 bg-emerald-600 text-white rounded-full text-xs font-black uppercase tracking-[0.2em] hover:bg-emerald-700 transition shadow-xl shadow-emerald-500/30 active:scale-95">
                        {{ $editingId ? 'Broadcast Updates' : 'Launch Recruitment' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>