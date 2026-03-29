<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight text-amber-900">Investment Opportunities
            </h2>
            <p class="text-sm text-gray-500 font-medium tracking-tight">Curate and promote high-value economic prospects
            </p>
        </div>
        <button wire:click="openCreate"
            class="flex items-center gap-2 px-6 py-3 bg-amber-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-amber-700 transition shadow-lg shadow-amber-500/20 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>Register Prospect
        </button>
    </div>

    <div class="mb-6 flex items-center justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search investment registry..."
                class="w-full pl-12 pr-4 py-3 bg-white border-2 border-gray-100 rounded-2xl text-sm font-medium focus:border-amber-500 focus:outline-none transition-all shadow-sm">
        </div>
        <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-xl border border-gray-100">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Active Leads:</span>
            <span class="text-xs font-black text-amber-600 tracking-tighter">{{ $investments->total() }}</span>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border-2 border-gray-50 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/50 border-b-2 border-gray-50">
                <tr>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Investment Lead</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Asset Class</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Economic Sector</th>
                    <th class="text-right px-8 py-5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($investments as $i)
                    <tr class="hover:bg-amber-50/20 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                @if($i->image_url)
                                    <div
                                        class="w-12 h-12 rounded-xl overflow-hidden border-2 border-white shadow-sm hover:scale-105 transition-transform">
                                        <img src="{{ config('app.url') . $i->image_url }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div
                                        class="w-12 h-12 rounded-xl bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center text-gray-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.407 2.5 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.407-2.5-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="font-bold text-gray-900 group-hover:text-amber-600 transition-colors">
                                    {{ $i->title_en }}</div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span
                                class="px-3 py-1 bg-amber-50 text-amber-700 rounded-lg text-[10px] font-black uppercase tracking-widest border border-amber-100">
                                {{ $i->category ?: 'General' }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-tight">
                                {{ $i->sector ?: 'Diversified' }}</div>
                        </td>
                        <td class="px-8 py-5 text-right space-x-2">
                            <button wire:click="openEdit({{ $i->id }})"
                                class="inline-flex items-center justify-center w-10 h-10 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="delete({{ $i->id }})"
                                wire:confirm="Archive and delete this investment lead?"
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
                            <div class="text-gray-300 font-black uppercase tracking-widest text-xs">No Investment
                                Assets Registered</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-8 py-6 bg-gray-50/30 border-t border-gray-50">{{ $investments->links() }}</div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div
                class="bg-white rounded-[3rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col animate-modal-up">
                <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">
                            {{ $editingId ? 'Refine' : 'Register' }} Opportunity</h3>
                        <p class="text-[10px] font-black text-amber-600 uppercase tracking-[0.3em] mt-1">Investment Gateway
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
                    <div class="flex gap-4 border-b-2 border-gray-100 pb-4">
                        <button wire:click="$set('activeTab', 'en')"
                            class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition {{ $activeTab === 'en' ? 'bg-amber-600 text-white shadow-lg shadow-amber-500/30' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">English</button>
                        <button wire:click="$set('activeTab', 'am')"
                            class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition {{ $activeTab === 'am' ? 'bg-amber-600 text-white shadow-lg shadow-amber-500/30' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">አማርኛ</button>
                        <button wire:click="$set('activeTab', 'or')"
                            class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition {{ $activeTab === 'or' ? 'bg-amber-600 text-white shadow-lg shadow-amber-500/30' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">Afaan Oromo</button>
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        {{-- MULTILINGUAL FIELDS --}}
                        <div class="col-span-2 space-y-6">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Investment Title</label>
                                @if($activeTab === 'en')
                                    <input wire:model="title_en" placeholder="e.g. Modern Industrial Agro-Processing Hub" class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900">
                                @elseif($activeTab === 'am')
                                    <input wire:model="title_am" placeholder="የርዕስ ትርጉም..." class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900">
                                @else
                                    <input wire:model="title_or" placeholder="Mata duree..." class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900">
                                @endif
                                @error('title_en') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Value Proposition & Potential</label>
                                @if($activeTab === 'en')
                                    <textarea wire:model="description_en" rows="3" placeholder="Detail the investment returns, scale, and strategic importance..." class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-3xl px-6 py-4 text-sm font-medium transition-all resize-none"></textarea>
                                @elseif($activeTab === 'am')
                                    <textarea wire:model="description_am" rows="3" placeholder="የማብራሪያ ትርጉም..." class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-3xl px-6 py-4 text-sm font-medium transition-all resize-none"></textarea>
                                @else
                                    <textarea wire:model="description_or" rows="3" placeholder="Ibsa..." class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-3xl px-6 py-4 text-sm font-medium transition-all resize-none"></textarea>
                                @endif
                            </div>
                            
                            <div class="grid grid-cols-2 gap-8">
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Location / Region</label>
                                    @if($activeTab === 'en')
                                        <input wire:model="location" placeholder="e.g. Sheger City" class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @elseif($activeTab === 'am')
                                        <input wire:model="location_am" placeholder="የቦታ ትርጉም..." class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @else
                                        <input wire:model="location_or" placeholder="Iddoo..." class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @endif
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Government Incentives</label>
                                    @if($activeTab === 'en')
                                        <input wire:model="incentives_en" placeholder="e.g. 5-Year Tax Holiday" class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @elseif($activeTab === 'am')
                                        <input wire:model="incentives_am" placeholder="የማበረታቻ ትርጉም..." class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @else
                                        <input wire:model="incentives_or" placeholder="Jajjabeessituu..." class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- UNIVERSAL FIELDS --}}
                        <div class="col-span-2 border-t-2 border-gray-100 pt-6 mt-4">
                            <h4 class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-6">Universal Asset Data</h4>
                            <div class="grid grid-cols-3 gap-6 mb-6">
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Asset Classification</label>
                                    <input wire:model="category" placeholder="PPP, Private Equity" class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @error('category') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Economic Sector</label>
                                    <input wire:model="sector" placeholder="Agriculture, Energy" class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @error('sector') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Estimated Budget</label>
                                    <input wire:model="budget" placeholder="$50M - $100M" class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @error('budget') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-6 mb-6">
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Contact Name</label>
                                    <input wire:model="contact_name" class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @error('contact_name') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Contact Phone</label>
                                    <input wire:model="contact_phone" class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @error('contact_phone') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Contact Email</label>
                                    <input wire:model="contact_email" type="email" class="w-full bg-gray-50 border-2 border-transparent focus:border-amber-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                                    @error('contact_email') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <label
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Marketing
                                Visual (Featured Image)</label>
                            <div
                                class="relative flex items-center justify-center p-6 bg-amber-50/50 border-2 border-dashed border-amber-200 rounded-3xl hover:border-amber-400 transition cursor-pointer">
                                <input type="file" wire:model="image" class="absolute inset-0 opacity-0 cursor-pointer">
                                <div
                                    class="text-[10px] font-black text-amber-600 uppercase tracking-widest flex items-center gap-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $image ? 'ASSET VISUALIZED' : 'UPLOAD PROMOTIONAL IMAGE' }}
                                </div>
                            </div>
                            <div wire:loading wire:target="image"
                                class="text-[9px] text-amber-500 font-black mt-2 animate-pulse text-center">UPDATING
                                PROMOTIONAL ASSETS...</div>
                        </div>
                    </div>
                </div>

                <div class="px-10 py-10 bg-gray-50/50 border-t border-gray-50 flex items-center justify-end gap-3">
                    <button wire:click="$set('showModal', false)"
                        class="px-8 py-3 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-red-500 transition">Withdraw
                        Proposal</button>
                    <button wire:click="save"
                        class="px-12 py-4 bg-amber-600 text-white rounded-full text-xs font-black uppercase tracking-[0.2em] hover:bg-amber-700 transition shadow-xl shadow-amber-500/30 active:scale-95">
                        {{ $editingId ? 'Authorize Updates' : 'Publish Opportunity' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>