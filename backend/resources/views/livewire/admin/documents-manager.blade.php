<div x-data="{ view: localStorage.getItem('docs_view') || 'table' }"
    x-init="$watch('view', v => localStorage.setItem('docs_view', v))">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight text-indigo-900">Digital Resource Archive
            </h2>
            <p class="text-sm text-gray-500 font-medium tracking-tight">Manage official publications, policies, and
                documents</p>
        </div>
        <div class="flex items-center gap-4">
            {{-- View Toggle --}}
            <div class="flex bg-gray-50 rounded-2xl p-1.5 border border-gray-100">
                <button @click="view = 'table'"
                    :class="view === 'table' ? 'bg-white shadow text-indigo-600' : 'text-gray-400 hover:text-gray-600'"
                    class="p-2.5 rounded-xl transition-all" title="Table View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </button>
                <button @click="view = 'grid'"
                    :class="view === 'grid' ? 'bg-white shadow text-indigo-600' : 'text-gray-400 hover:text-gray-600'"
                    class="p-2.5 rounded-xl transition-all" title="Grid View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </button>
            </div>
            <button wire:click="openCreate"
                class="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/20 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>Archive New Document
            </button>
        </div>
    </div>

    {{-- Search --}}
    <div class="mb-6 flex items-center gap-4">
        <div class="relative flex-1 max-w-md">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search archive by title..."
                class="w-full pl-12 pr-4 py-3 bg-white border-2 border-gray-100 rounded-2xl text-sm font-medium focus:border-indigo-500 focus:outline-none transition-all shadow-sm">
        </div>
    </div>

    {{-- TABLE VIEW --}}
    <div x-show="view === 'table'" class="bg-white rounded-[2.5rem] border-2 border-gray-50 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/50 border-b-2 border-gray-50">
                <tr>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Document Details</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Sector / Source</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Classification</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Archived Date</th>
                    <th class="text-right px-8 py-5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($documents as $doc)
                    <tr class="hover:bg-indigo-50/20 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-16 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden border border-gray-200">
                                    @if($doc->cover_image_url)
                                        <img src="{{ asset($doc->cover_image_url) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                        {{ $doc->title_en }}</div>
                                    <div class="text-[9px] text-gray-400 font-black uppercase tracking-widest flex items-center gap-1.5 mt-0.5">
                                        <svg class="w-2.5 h-2.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        {{ $doc->file_url ? strtoupper(pathinfo($doc->file_url, PATHINFO_EXTENSION)) . ' Document' : 'No Attachment' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-[10px] font-black uppercase text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                                {{ $doc->author ?: 'Official Resource' }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <span
                                class="bg-gray-50 text-gray-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-gray-100">
                                {{ $doc->category ?: 'General' }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-xs font-bold text-gray-500">{{ $doc->created_at->format('M d, Y') }}</div>
                        </td>
                        <td class="px-8 py-5 text-right space-x-2">
                            @if($doc->file_url)
                                <a href="{{ asset($doc->file_url) }}" target="_blank"
                                    class="inline-flex items-center justify-center w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            @endif
                            <button wire:click="openEdit({{ $doc->id }})"
                                class="inline-flex items-center justify-center w-10 h-10 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="delete({{ $doc->id }})" wire:confirm="Permanently delete this document?"
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
                            <div class="text-gray-300 font-black uppercase tracking-widest text-xs">Archive is Empty
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-8 py-6 bg-gray-50/30 border-t border-gray-50">{{ $documents->links() }}</div>
    </div>

    {{-- GRID VIEW --}}
    <div x-show="view === 'grid'" x-cloak>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
            @forelse($documents as $doc)
                <div
                    class="group bg-white rounded-3xl overflow-hidden border-2 border-gray-50 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="aspect-[3/4] relative overflow-hidden bg-indigo-50">
                        @if($doc->cover_image_url)
                            <img src="{{ asset($doc->cover_image_url) }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-indigo-200 p-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        @endif
                        <div
                            class="absolute inset-0 bg-indigo-900/80 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center gap-2 p-3">
                            @if($doc->file_url)
                                <a href="{{ asset($doc->file_url) }}" target="_blank"
                                    class="w-9 h-9 bg-white text-indigo-700 rounded-full flex items-center justify-center hover:bg-indigo-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            @endif
                            <button wire:click="openEdit({{ $doc->id }})"
                                class="w-9 h-9 bg-white text-blue-600 rounded-full flex items-center justify-center hover:bg-blue-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="delete({{ $doc->id }})" wire:confirm="Delete this document?"
                                class="w-9 h-9 bg-white text-red-500 rounded-full flex items-center justify-center hover:bg-red-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1">
                            {{ $doc->category ?: '—' }}</div>
                        <div class="text-xs font-bold text-gray-900 line-clamp-2 leading-tight">{{ $doc->title_en }}</div>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full text-center py-20 text-gray-300 font-black text-xs uppercase tracking-widest">
                    Archive is Empty</div>
            @endforelse
        </div>
        <div class="mt-8">{{ $documents->links() }}</div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div
                class="bg-white rounded-[3rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col animate-modal-up">
                <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">
                            {{ $editingId ? 'Refine' : 'Catalog' }} Asset
                        </h3>
                        <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] mt-1">Resource Archive
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
                    <div class="flex items-center gap-2 p-1 bg-gray-50 rounded-2xl w-fit">
                        <button wire:click="$set('activeTab', 'en')"
                            class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $activeTab === 'en' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-400 hover:text-gray-600' }}">English</button>
                        <button wire:click="$set('activeTab', 'am')"
                            class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $activeTab === 'am' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-400 hover:text-gray-600' }}">Amharic</button>
                        <button wire:click="$set('activeTab', 'or')"
                            class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $activeTab === 'or' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-400 hover:text-gray-600' }}">Oromo</button>
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        <div class="col-span-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">
                                Document Title ({{ strtoupper($activeTab) }})
                            </label>

                            @if($activeTab === 'en')
                                <input wire:model="title_en" placeholder="e.g. Annual Strategic Growth Plan"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900 shadow-sm">
                                @error('title_en') <p class="text-red-500 text-[10px] mt-2 font-black italic">{{ $message }}</p> @enderror
                            @elseif($activeTab === 'am')
                                <input wire:model="title_am" placeholder="የዓመታዊ ስትራቴጂክ ዕድገት ዕቅድ"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900 shadow-sm">
                                @error('title_am') <p class="text-red-500 text-[10px] mt-2 font-black italic">{{ $message }}</p> @enderror
                            @elseif($activeTab === 'or')
                                <input wire:model="title_or" placeholder="Karoora Guddina Istaatireejii Waggaa"
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900 shadow-sm">
                                @error('title_or') <p class="text-red-500 text-[10px] mt-2 font-black italic">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">
                                Sector / Issuing Body
                            </label>
                            <input wire:model="author" list="sector-list" placeholder="e.g. Finance Bureau"
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm transition-all shadow-sm">
                            <datalist id="sector-list">
                                @foreach($sectors as $sector)
                                    <option value="{{ $sector->{'name_' . $activeTab} ?? $sector->name_en }}">
                                @endforeach
                            </datalist>
                            @error('author') <p class="text-red-500 text-[10px] mt-2 font-black italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">
                                Asset Classification
                            </label>
                            <input wire:model="category" list="category-list" placeholder="e.g. Policy, Regulation, Report"
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm transition-all shadow-sm">
                            <datalist id="category-list">
                                <option value="Policy">
                                <option value="Regulation">
                                <option value="Report">
                                <option value="Manual">
                                <option value="Guideline">
                                <option value="Strategic Plan">
                                <option value="Budget">
                                <option value="Directive">
                            </datalist>
                            @error('category') <p class="text-red-500 text-[10px] mt-2 font-black italic">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Publication
                                Date</label>
                            <input type="date" value="{{ date('Y-m-d') }}" readonly
                                class="w-full bg-gray-50 border-2 border-transparent rounded-2xl px-6 py-4 font-bold text-sm text-gray-400 cursor-not-allowed">
                        </div>

                        <div class="col-span-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Main
                                Resource (PDF)</label>
                            <div
                                class="relative flex items-center justify-center p-4 bg-indigo-50/50 border-2 border-dashed border-indigo-200 rounded-2xl hover:border-indigo-400 transition cursor-pointer">
                                <input type="file" wire:model="file" class="absolute inset-0 opacity-0 cursor-pointer">
                                <div
                                    class="text-[10px] font-black text-indigo-600 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    {{ $file ? 'PDF READY' : 'ARCHIVE PDF' }}
                                </div>
                            </div>
                            @error('file') <p class="text-red-500 text-[10px] mt-2 font-black italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Cover
                                Art (Optional)</label>
                            <div
                                class="relative flex items-center justify-center p-4 bg-blue-50/50 border-2 border-dashed border-blue-200 rounded-2xl hover:border-blue-400 transition cursor-pointer">
                                <input type="file" wire:model="cover_image"
                                    class="absolute inset-0 opacity-0 cursor-pointer">
                                <div
                                    class="text-[10px] font-black text-blue-600 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $cover_image ? 'ARTWORK CACHED' : 'UPLOAD COVER' }}
                                </div>
                            </div>
                            @error('cover_image') <p class="text-red-500 text-[10px] mt-2 font-black italic">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div wire:loading wire:target="file, cover_image"
                        class="text-[9px] text-indigo-500 font-bold animate-pulse text-center">SYNCHRONIZING DIGITAL
                        ASSETS...</div>
                </div>

                <div class="px-10 py-10 bg-gray-50/50 border-t border-gray-50 flex items-center justify-end gap-3">
                    <button wire:click="$set('showModal', false)"
                        class="px-8 py-3 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-red-500 transition">Discard
                        Asset</button>
                    <button wire:click="save" wire:loading.attr="disabled"
                        class="px-12 py-4 bg-indigo-600 text-white rounded-full text-xs font-black uppercase tracking-[0.2em] hover:bg-indigo-700 transition shadow-xl shadow-indigo-500/30 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="save">{{ $editingId ? 'Refine Record' : 'Commit to Archive' }}</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>