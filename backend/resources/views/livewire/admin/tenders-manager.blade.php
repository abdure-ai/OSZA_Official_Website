<div x-data="{ view: localStorage.getItem('tenders_view') || 'table' }"
    x-init="$watch('view', v => localStorage.setItem('tenders_view', v))">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Tenders &amp; Procurement</h2>
            <p class="text-sm text-gray-500 font-medium">Manage official bid notices and procurement documents</p>
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
                </svg>Issue New Tender
            </button>
        </div>
    </div>

    {{-- TABLE VIEW --}}
    <div x-show="view === 'table'" class="bg-white rounded-[2.5rem] border-2 border-gray-50 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/50 border-b-2 border-gray-50">
                <tr>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Tender Details</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Reference</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Status</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Deadline</th>
                    <th class="text-right px-8 py-5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($tenders as $t)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                {{ $t->title_en }}</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">{{ Str::limit($t->description_en, 60) }}
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span
                                class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">{{ $t->ref_number ?: 'NO-REF' }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <span
                                class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $t->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $t->status }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-xs font-bold text-gray-500">
                                {{ $t->deadline ? \Carbon\Carbon::parse($t->deadline)->format('M d, Y') : 'Indefinite' }}
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right space-x-2">
                            <button wire:click="openEdit({{ $t->id }})"
                                class="inline-flex items-center justify-center w-10 h-10 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="delete({{ $t->id }})" wire:confirm="Permanently delete this tender?"
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
                            <div class="text-gray-300 font-black uppercase tracking-widest text-xs">No Active Tenders</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-8 py-6 bg-gray-50/30 border-t border-gray-50">{{ $tenders->links() }}</div>
    </div>

    {{-- GRID VIEW --}}
    <div x-show="view === 'grid'" x-cloak>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($tenders as $t)
                <div
                    class="group bg-white rounded-3xl border-2 border-gray-50 shadow-sm p-7 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col gap-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <span
                                class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $t->ref_number ?: 'NO-REF' }}</span>
                            <h3
                                class="font-bold text-gray-900 mt-1 group-hover:text-blue-600 transition-colors leading-tight">
                                {{ $t->title_en }}</h3>
                        </div>
                        <span
                            class="flex-shrink-0 px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest {{ $t->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $t->status }}</span>
                    </div>
                    @if($t->description_en)
                        <p class="text-xs text-gray-400 line-clamp-2">{{ $t->description_en }}</p>
                    @endif
                    <div class="flex items-center justify-between mt-auto">
                        <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest">
                            {{ $t->deadline ? \Carbon\Carbon::parse($t->deadline)->format('M d, Y') : 'No Deadline' }}</div>
                        <div class="flex gap-2">
                            <button wire:click="openEdit({{ $t->id }})"
                                class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="delete({{ $t->id }})" wire:confirm="Delete this tender?"
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
                <div class="col-span-full text-center py-20 text-gray-300 font-black text-xs uppercase tracking-widest">No
                    Active Tenders</div>
            @endforelse
        </div>
        <div class="mt-8">{{ $tenders->links() }}</div>
    </div>


    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div
                class="bg-white rounded-[3rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col animate-modal-up">
                <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">
                            {{ $editingId ? 'Refine' : 'Issue' }} Tender
                        </h3>
                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mt-1">Official Procurement
                            Portal</p>
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
                    <div class="grid grid-cols-2 gap-8">
                        <div class="col-span-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Tender
                                Title (Official)</label>
                            <input wire:model="title_en" placeholder="Enter tender title..."
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900">
                            @error('title_en') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Detailed
                                Scope of Work</label>
                            <textarea wire:model="description_en" rows="4"
                                placeholder="Describe the procurement requirements..."
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-3xl px-6 py-4 text-sm font-medium transition-all resize-none"></textarea>
                        </div>

                        <div>
                            <label
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Reference
                                Number</label>
                            <input wire:model="ref_number" placeholder="REF/{{ date('Y') }}/..."
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                        </div>

                        <div>
                            <label
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Submission
                                Status</label>
                            <select wire:model="status"
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm outline-none appearance-none">
                                <option value="open">ACTIVE / OPEN</option>
                                <option value="closed">CLOSED / ARCHIVED</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Closing
                                Date</label>
                            <input type="date" wire:model="deadline"
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Tender
                                Document (PDF)</label>
                            <div
                                class="relative flex items-center justify-center p-4 bg-blue-50/50 border-2 border-dashed border-blue-200 rounded-2xl hover:border-blue-400 transition cursor-pointer">
                                <input type="file" wire:model="document" class="absolute inset-0 opacity-0 cursor-pointer">
                                <div
                                    class="text-[10px] font-black text-blue-600 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    {{ $document ? 'FILE SELECTED' : 'UPLOAD PDF' }}
                                </div>
                            </div>
                            <div wire:loading wire:target="document"
                                class="text-[9px] text-blue-500 font-bold mt-2 animate-pulse">PROCESSING DATA...</div>
                        </div>
                    </div>
                </div>

                <div class="px-10 py-10 bg-gray-50/50 border-t border-gray-50 flex items-center justify-end gap-3">
                    <button wire:click="$set('showModal', false)"
                        class="px-8 py-3 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-red-500 transition">Abort
                        Submission</button>
                    <button wire:click="save"
                        class="px-12 py-4 bg-blue-600 text-white rounded-full text-xs font-black uppercase tracking-[0.2em] hover:bg-blue-700 transition shadow-xl shadow-blue-500/30 active:scale-95">
                        {{ $editingId ? 'Confirm Changes' : 'Publish Notice' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>