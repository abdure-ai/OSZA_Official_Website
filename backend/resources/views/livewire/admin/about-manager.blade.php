<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight text-left">About Page Manager</h2>
            <p class="text-sm text-gray-500 font-medium">Manage History, Mission, Vision, and Strategic Objectives</p>
        </div>
        @if($activeTab === 'objectives')
        <button wire:click="openCreate"
            class="flex items-center gap-2 px-6 py-3 bg-[#1a56db] text-white rounded-2xl text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>Add New Objective
        </button>
        @endif
    </div>

    {{-- Tabs (Woreda Style) --}}
    <div class="flex border-b border-gray-100 bg-gray-50/30 mb-8 rounded-t-[2rem] px-4">
        <button wire:click="setTab('history')"
            class="px-8 py-4 text-[10px] font-black uppercase tracking-[0.2em] border-b-2 transition-all {{ $activeTab === 'history' ? 'border-blue-600 text-blue-600 bg-white shadow-sm' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
            History
        </button>
        <button wire:click="setTab('mission')"
            class="px-8 py-4 text-[10px] font-black uppercase tracking-[0.2em] border-b-2 transition-all {{ $activeTab === 'mission' ? 'border-blue-600 text-blue-600 bg-white shadow-sm' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
            Mission
        </button>
        <button wire:click="setTab('vision')"
            class="px-8 py-4 text-[10px] font-black uppercase tracking-[0.2em] border-b-2 transition-all {{ $activeTab === 'vision' ? 'border-blue-600 text-blue-600 bg-white shadow-sm' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
            Vision
        </button>
        <button wire:click="setTab('objectives')"
            class="px-8 py-4 text-[10px] font-black uppercase tracking-[0.2em] border-b-2 transition-all {{ $activeTab === 'objectives' ? 'border-blue-600 text-blue-600 bg-white shadow-sm' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
            Objectives
        </button>
    </div>

    {{-- Content Area (Woreda Style) --}}
    <div class="bg-white rounded-[2rem] border-2 border-gray-50 shadow-sm overflow-hidden p-8">
        @if(in_array($activeTab, ['history', 'mission', 'vision']))
            <form wire:submit="save" class="space-y-8">
                <div class="space-y-10">
                    <div class="space-y-8">
                        <div x-data="{ lang: 'en' }">
                            <div class="flex gap-4 border-b border-gray-100 mb-8 bg-gray-50/50 p-2 rounded-xl">
                                <button type="button" @click="lang = 'en'"
                                    :class="lang === 'en' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600'"
                                    class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">English</button>
                                <button type="button" @click="lang = 'am'"
                                    :class="lang === 'am' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600'"
                                    class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">አማርኛ</button>
                                <button type="button" @click="lang = 'or'"
                                    :class="lang === 'or' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600'"
                                    class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">Oromo</button>
                            </div>

                            <div x-show="lang === 'en'" class="space-y-6">
                                <div>
                                    <label class="label-badge">Section Title (EN)</label>
                                    <input wire:model="title_en" class="admin-input">
                                </div>
                                <div>
                                    <label class="label-badge">Main Content (EN)</label>
                                    <textarea wire:model="content_en" rows="10" class="admin-input resize-none"></textarea>
                                </div>
                            </div>

                            <div x-show="lang === 'am'" class="space-y-6" style="display:none">
                                <div>
                                    <label class="label-badge">Section Title (AM)</label>
                                    <input wire:model="title_am" class="admin-input">
                                </div>
                                <div>
                                    <label class="label-badge">Main Content (AM)</label>
                                    <textarea wire:model="content_am" rows="10" class="admin-input resize-none"></textarea>
                                </div>
                            </div>

                            <div x-show="lang === 'or'" class="space-y-6" style="display:none">
                                <div>
                                    <label class="label-badge">Section Title (OR)</label>
                                    <input wire:model="title_or" class="admin-input">
                                </div>
                                <div>
                                    <label class="label-badge">Main Content (OR)</label>
                                    <textarea wire:model="content_or" rows="10" class="admin-input resize-none"></textarea>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-10 border-t border-gray-50">
                        @if($activeTab === 'history')
                        <div class="bg-gray-50 p-8 rounded-[2rem] border-2 border-dashed border-gray-100">
                            <label class="label-badge mb-6 px-1">Background Image</label>
                            @if($image || $image_url)
                                <div class="relative group rounded-2xl overflow-hidden shadow-lg h-64 border-4 border-white">
                                    <img src="{{ $image ? $image->temporaryUrl() : asset($image_url) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                                    <label class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center cursor-pointer">
                                        <span class="text-white text-[10px] font-black uppercase tracking-widest bg-white/20 backdrop-blur-md px-4 py-2 rounded-xl">Change Image</span>
                                        <input type="file" wire:model="image" class="hidden">
                                    </label>
                                </div>
                            @else
                                <label class="flex flex-col items-center justify-center w-full h-64 bg-white border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition group">
                                    <svg class="w-12 h-12 text-gray-200 group-hover:scale-110 transition duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" /></svg>
                                    <span class="mt-4 text-[10px] font-black text-gray-300 uppercase tracking-widest">Upload Header</span>
                                    <input type="file" wire:model="image" class="hidden">
                                </label>
                            @endif
                            <div wire:loading wire:target="image" class="mt-4 text-[10px] font-black text-blue-600 animate-pulse uppercase tracking-widest text-center">Processing...</div>
                        </div>
                        @endif

                        <div class="bg-gray-50 p-8 rounded-[2rem] border-2 border-gray-100 flex flex-col items-center justify-center gap-4">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Visibility Status</span>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative w-12 h-6 bg-gray-200 rounded-full transition-colors group-hover:bg-gray-300">
                                    <input type="checkbox" wire:model="is_active" class="absolute inset-0 opacity-0 cursor-pointer peer">
                                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all peer-checked:left-7 peer-checked:bg-blue-600 shadow-sm"></div>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 peer-checked:text-blue-600">Active</span>
                            </label>
                        </div>

                        <div class="bg-blue-50/30 p-8 rounded-[2rem] border-2 border-blue-50/50 flex flex-col items-center justify-center text-center {{ $activeTab !== 'history' ? 'md:col-span-2' : '' }}">
                            <p class="text-[10px] font-black uppercase tracking-widest text-blue-400 mb-6 font-bold">Persistence Layer</p>
                            <button type="submit"
                                class="w-full py-4 bg-blue-600 text-white rounded-full text-xs font-black uppercase tracking-[0.2em] hover:bg-blue-700 transition shadow-xl shadow-blue-500/30 active:scale-95">
                                <span wire:loading.remove>Save Changes</span>
                                <span wire:loading>Processing...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @else
            {{-- Objectives List (Woreda style) --}}
            <div class="space-y-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 tracking-tight">Strategic Objectives</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Institutional Goals & Milestones</p>
                    </div>
                </div>

                <div class="bg-gray-50/50 rounded-[2rem] border-2 border-gray-100/50 overflow-hidden shadow-sm">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/80 border-b-2 border-gray-100">
                                <th class="text-left px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest w-20">No.</th>
                                <th class="text-left px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Description / Goal</th>
                                <th class="text-left px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest w-32">Status</th>
                                <th class="text-right px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest w-40">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white/50">
                            @forelse($objectives as $obj)
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-6 py-5 font-black text-gray-300 text-xs">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $obj->title_en }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $obj->is_active ? 'bg-green-500 animate-pulse' : 'bg-gray-300' }}"></span>
                                            <span class="text-[10px] font-black uppercase tracking-tighter {{ $obj->is_active ? 'text-green-600' : 'text-gray-400' }}">
                                                {{ $obj->is_active ? 'Active' : 'Hidden' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button wire:click="editObjective({{ $obj->id }})" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                                            <button wire:click="delete({{ $obj->id }})" wire:confirm="Delete this objective?" class="p-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-20 bg-white">
                                        <div class="text-[10px] font-black uppercase tracking-widest text-gray-300">No Objectives Defined</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-8">{{ $objectives->links() }}</div>
            </div>
        @endif
    </div>

    {{-- Modal (Woreda style) --}}
    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-data="{ tab: 'en' }">
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col" @click.stop x-transition.opacity>
                <div class="border-b border-gray-50 px-8 py-6 flex items-center justify-between bg-white relative">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 tracking-tight">{{ $editingId ? 'Edit' : 'Create' }} Objective</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Institutional Goal Management</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Tabs -->
                <div class="flex border-b border-gray-50 bg-gray-50/50 px-8">
                    <button @click="tab = 'en'" :class="tab === 'en' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-400 hover:text-gray-600'" class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] border-b-2 transition-all">English</button>
                    <button @click="tab = 'am'" :class="tab === 'am' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-400 hover:text-gray-600'" class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] border-b-2 transition-all">አማርኛ</button>
                    <button @click="tab = 'or'" :class="tab === 'or' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-400 hover:text-gray-600'" class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] border-b-2 transition-all">Afaan Oromo</button>
                    <button @click="tab = 'config'" :class="tab === 'config' ? 'border-blue-600 text-blue-600 bg-white shadow-inner shadow-gray-50' : 'border-transparent text-gray-400'" class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] border-b-2 transition-all">Config</button>
                </div>

                <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                    <div x-show="tab === 'en'" class="space-y-6">
                        <div>
                            <label class="label-badge text-blue-600">Objective Title (EN) *</label>
                            <input wire:model="title_en" class="admin-input" placeholder="Title for the objective...">
                            @error('title_en') <p class="text-red-500 text-[10px] mt-1 font-black uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div x-show="tab === 'am'" class="space-y-6" style="display:none">
                        <div>
                            <label class="label-badge text-blue-600">Objective Title (AM)</label>
                            <input wire:model="title_am" class="admin-input" placeholder="አማርኛ">
                        </div>
                    </div>
                    <div x-show="tab === 'or'" class="space-y-6" style="display:none">
                        <div>
                            <label class="label-badge text-blue-600">Objective Title (OR)</label>
                            <input wire:model="title_or" class="admin-input" placeholder="Afaan Oromoo">
                        </div>
                    </div>
                    <div x-show="tab === 'config'" class="space-y-6" style="display:none">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="bg-gray-50 border-2 border-gray-100 rounded-[2rem] p-8">
                                <label class="label-badge mb-4">Display Rank</label>
                                <input type="number" wire:model="sort_order" class="admin-input">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-4 leading-relaxed">Lower numbers appear first in the public view.</p>
                            </div>
                            <div class="bg-gray-50 border-2 border-gray-100 rounded-[2rem] p-8 flex items-center justify-center">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative w-12 h-6 bg-gray-200 rounded-full transition-colors group-hover:bg-gray-300">
                                        <input type="checkbox" wire:model="is_active" class="absolute inset-0 opacity-0 cursor-pointer peer">
                                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all peer-checked:left-7 peer-checked:bg-blue-600 shadow-sm"></div>
                                    </div>
                                    <span class="text-xs font-black uppercase tracking-widest text-gray-400 peer-checked:text-blue-600">Publicly Active</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-50 px-8 py-8 flex justify-end gap-3 bg-gray-50/30">
                    <button wire:click="$set('showModal', false)" class="px-8 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 transition">Discard</button>
                    <button wire:click="save" class="px-12 py-4 bg-blue-600 text-white rounded-full text-xs font-black uppercase tracking-[0.2em] hover:bg-blue-700 transition shadow-xl shadow-blue-500/30 active:scale-95">
                        {{ $editingId ? 'Push Updates' : 'Launch Objective' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <style>
        .label-badge {
            @apply text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1.5;
        }

        .admin-input {
            @apply w-full bg-gray-50 border-2 border-gray-100 focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-900 transition-all outline-none;
        }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</div>