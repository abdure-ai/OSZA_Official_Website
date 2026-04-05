<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900">About Page Manager</h2>
            <p class="text-sm text-gray-500 mt-1">Manage History, Mission, Vision, and Objectives.</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex flex-wrap gap-2 mb-6 border-b border-gray-100 pb-px">
        <button wire:click="setTab('history')"
            class="px-4 py-2 text-sm font-medium transition-all border-b-2 {{ $activeTab === 'history' ? 'border-[#1a56db] text-[#1a56db]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            History
        </button>
        <button wire:click="setTab('mission')"
            class="px-4 py-2 text-sm font-medium transition-all border-b-2 {{ $activeTab === 'mission' ? 'border-[#1a56db] text-[#1a56db]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            Mission
        </button>
        <button wire:click="setTab('vision')"
            class="px-4 py-2 text-sm font-medium transition-all border-b-2 {{ $activeTab === 'vision' ? 'border-[#1a56db] text-[#1a56db]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            Vision
        </button>
        <button wire:click="setTab('objectives')"
            class="px-4 py-2 text-sm font-medium transition-all border-b-2 {{ $activeTab === 'objectives' ? 'border-[#1a56db] text-[#1a56db]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            Objectives
        </button>
    </div>

    {{-- Content Area --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden p-6">
        @if(in_array($activeTab, ['history', 'mission', 'vision']))
            <form wire:submit="save" class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-6">
                        <div x-data="{ lang: 'en' }">
                            <div class="flex gap-4 border-b border-gray-100 mb-6">
                                <button type="button" @click="lang = 'en'"
                                    :class="lang === 'en' ? 'border-[#1a56db] text-[#1a56db]' : 'border-transparent text-gray-400'"
                                    class="pb-2 border-b-2 text-xs font-bold uppercase tracking-wider transition">English</button>
                                <button type="button" @click="lang = 'am'"
                                    :class="lang === 'am' ? 'border-[#1a56db] text-[#1a56db]' : 'border-transparent text-gray-400'"
                                    class="pb-2 border-b-2 text-xs font-bold uppercase tracking-wider transition">Amharic</button>
                                <button type="button" @click="lang = 'or'"
                                    :class="lang === 'or' ? 'border-[#1a56db] text-[#1a56db]' : 'border-transparent text-gray-400'"
                                    class="pb-2 border-b-2 text-xs font-bold uppercase tracking-wider transition">Oromo</button>
                            </div>
 
                            <div x-show="lang === 'en'" class="space-y-5">
                                <div>
                                    <label class="label-badge">Title (EN)</label>
                                    <input wire:model="title_en" class="admin-input">
                                </div>
                                <div>
                                    <label class="label-badge">Content (EN)</label>
                                    <textarea wire:model="content_en" rows="8" class="admin-input"></textarea>
                                </div>
                            </div>
 
                            <div x-show="lang === 'am'" class="space-y-5" style="display:none">
                                <div>
                                    <label class="label-badge">Title (AM)</label>
                                    <input wire:model="title_am" class="admin-input">
                                </div>
                                <div>
                                    <label class="label-badge">Content (AM)</label>
                                    <textarea wire:model="content_am" rows="8" class="admin-input"></textarea>
                                </div>
                            </div>
 
                            <div x-show="lang === 'or'" class="space-y-5" style="display:none">
                                <div>
                                    <label class="label-badge">Title (OR)</label>
                                    <input wire:model="title_or" class="admin-input">
                                </div>
                                <div>
                                    <label class="label-badge">Content (OR)</label>
                                    <textarea wire:model="content_or" rows="8" class="admin-input"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
 
                    <div class="space-y-6">
                        @if($activeTab === 'history')
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                            <label class="label-badge mb-4">Background Image</label>
                            @if($image || $image_url)
                                <div class="relative group rounded-xl overflow-hidden shadow-sm h-40 border border-gray-200">
                                    <img src="{{ $image ? $image->temporaryUrl() : asset($image_url) }}" class="w-full h-full object-cover">
                                    <label class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center cursor-pointer">
                                        <span class="text-white text-xs font-bold px-3 py-1 bg-white/20 backdrop-blur-md rounded-lg">Change</span>
                                        <input type="file" wire:model="image" class="hidden">
                                    </label>
                                </div>
                            @else
                                <label class="flex flex-col items-center justify-center w-full h-40 bg-white border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition group">
                                    <svg class="w-8 h-8 text-gray-300 group-hover:scale-110 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" /></svg>
                                    <input type="file" wire:model="image" class="hidden">
                                </label>
                            @endif
                        </div>
                        @endif
 
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 flex items-center justify-between">
                            <span class="text-sm font-bold text-gray-700">Status</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-200 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1a56db]"></div>
                            </label>
                        </div>
                    </div>
                </div>
 
                <div class="flex justify-end pt-6 border-t border-gray-50">
                    <button type="submit"
                        class="bg-[#1a56db] text-white px-8 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 transition flex items-center gap-2">
                        <span wire:loading.remove>Save Changes</span>
                        <span wire:loading>Processing...</span>
                    </button>
                </div>
            </form>
        @else
            {{-- Objectives List (News style table) --}}
            <div class="space-y-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Strategic Objectives</h3>
                    <button wire:click="openCreateObjective"
                        class="px-4 py-2 bg-[#1a56db] text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Objective
                    </button>
                </div>
 
                <div class="border border-gray-100 rounded-xl overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-4 py-3 text-gray-500 font-semibold w-16">#</th>
                                <th class="text-left px-4 py-3 text-gray-500 font-semibold">Title</th>
                                <th class="text-left px-4 py-3 text-gray-500 font-semibold">Status</th>
                                <th class="text-right px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($objectives as $obj)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 font-medium text-gray-400">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 overflow-hidden">
                                        <span class="font-semibold text-gray-800">{{ $obj->title_en }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $obj->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $obj->is_active ? 'Active' : 'Hidden' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button wire:click="editObjective({{ $obj->id }})" class="text-blue-600 hover:text-blue-800 font-semibold px-2 py-1 hover:bg-blue-50 rounded transition">Edit</button>
                                            <button wire:click="delete({{ $obj->id }})" wire:confirm="Delete this objective?" class="text-red-500 hover:text-red-700 font-semibold px-2 py-1 hover:bg-red-50 rounded transition">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12 text-gray-400">No objectives found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $objectives->links() }}</div>
            </div>
        @endif
    </div>

    {{-- Modal (News style) --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-data x-show="true" x-transition.opacity>
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 bg-white z-10">
                    <h3 class="text-lg font-bold text-gray-900">{{ $editingId ? 'Edit' : 'New' }} Strategic Objective</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
 
                <div class="p-6 space-y-6">
                    {{-- Language Tabs --}}
                    <div x-data="{ lang: 'en' }">
                        <div class="flex gap-4 border-b border-gray-100 mb-6">
                            <button type="button" @click="lang = 'en'"
                                :class="lang === 'en' ? 'border-[#1a56db] text-[#1a56db]' : 'border-transparent text-gray-400'"
                                class="pb-2 border-b-2 text-xs font-bold uppercase tracking-wider transition">English</button>
                            <button type="button" @click="lang = 'am'"
                                :class="lang === 'am' ? 'border-[#1a56db] text-[#1a56db]' : 'border-transparent text-gray-400'"
                                class="pb-2 border-b-2 text-xs font-bold uppercase tracking-wider transition">Amharic</button>
                            <button type="button" @click="lang = 'or'"
                                :class="lang === 'or' ? 'border-[#1a56db] text-[#1a56db]' : 'border-transparent text-gray-400'"
                                class="pb-2 border-b-2 text-xs font-bold uppercase tracking-wider transition">Oromo</button>
                        </div>
 
                        <div x-show="lang === 'en'" class="space-y-4">
                            <div>
                                <label class="label-badge">Objective Title (EN) *</label>
                                <input wire:model="title_en" class="admin-input" placeholder="Enter objective title in English">
                                @error('title_en') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                            </div>
                        </div>
 
                        <div x-show="lang === 'am'" class="space-y-4" style="display:none">
                            <div>
                                <label class="label-badge">Objective Title (AM)</label>
                                <input wire:model="title_am" class="admin-input" placeholder="Enter objective title in Amharic">
                            </div>
                        </div>
 
                        <div x-show="lang === 'or'" class="space-y-4" style="display:none">
                            <div>
                                <label class="label-badge">Objective Title (OR)</label>
                                <input wire:model="title_or" class="admin-input" placeholder="Enter objective title in Oromo">
                            </div>
                        </div>
                    </div>
 
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label-badge">Sort Order</label>
                            <input wire:model="sort_order" type="number" class="admin-input">
                        </div>
                        <div class="flex items-end">
                            <label class="flex items-center gap-3 cursor-pointer p-3.5 px-6 bg-gray-50 border border-gray-100 rounded-xl w-full">
                                <input type="checkbox" wire:model="is_active" class="w-4 h-4 text-[#1a56db] rounded border-gray-300 focus:ring-[#1a56db]">
                                <span class="text-sm font-bold text-gray-700">Set as Active</span>
                            </label>
                        </div>
                    </div>
                </div>
 
                <div class="border-t border-gray-100 px-6 py-4 flex justify-end gap-3 sticky bottom-0 bg-white">
                    <button wire:click="$set('showModal', false)"
                        class="px-4 py-2 border border-gray-200 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">Cancel</button>
                    <button wire:click="save"
                        class="px-6 py-2 bg-[#1a56db] text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition">
                        {{ $editingId ? 'Update' : 'Save' }} Objective
                    </button>
                </div>
            </div>
        </div>
    @endif
 
    <style>
        .label-badge {
            @apply text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1.5;
        }
 
        .admin-input {
            @apply w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-900 focus:ring-2 focus:ring-[#1a56db]/20 focus:border-[#1a56db] transition duration-200 outline-none;
        }
    </style>
</div>