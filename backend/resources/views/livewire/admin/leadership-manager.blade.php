<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Leadership Manager</h2>
            <p class="text-sm text-gray-500 mt-1">Manage administrators and organize the organogram.</p>
        </div>
        <button wire:click="openCreate"
            class="px-4 py-2 bg-[#1a56db] text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add New Official
        </button>
    </div>

    {{-- Organogram Preview / List --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($leaders as $leader)
            <div
                class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 group relative">
                <div class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition">
                    <button wire:click="openEdit({{ $leader->id }})"
                        class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition"><svg
                            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg></button>
                    <button wire:click="delete({{ $leader->id }})" wire:confirm="Remove this official?"
                        class="p-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition"><svg
                            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg></button>
                </div>
 
                <div class="flex items-center gap-4">
                    @if($leader->photo_url)
                        <img src="{{ asset($leader->photo_url) }}"
                            class="w-16 h-16 rounded-xl object-cover border border-gray-100 flex-shrink-0">
                    @else
                        <div
                            class="w-16 h-16 rounded-xl bg-blue-50 flex items-center justify-center text-[#1a56db] font-bold text-xl border border-gray-100">
                            {{ substr($leader->name_en, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <span
                            class="inline-block px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded mb-1">Level
                            {{ $leader->hierarchy_level }}</span>
                        <h3 class="font-bold text-gray-900 leading-tight">{{ $leader->name_en }}</h3>
                        <p class="text-[10px] font-medium text-gray-500 mt-0.5 uppercase">{{ $leader->position_en }}</p>
                    </div>
                </div>
 
                @if($leader->parent)
                    <div class="mt-4 pt-4 border-t border-gray-50 flex items-center gap-2">
                        <span class="text-[10px] font-semibold text-gray-400">Reports To:</span>
                        <span class="text-[10px] font-bold text-[#1a56db]">{{ $leader->parent->name_en }}</span>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-400 bg-white rounded-xl border-2 border-dashed border-gray-100 font-medium">No officials found.</div>
        @endforelse
    </div>
    <div class="mt-8">{{ $leaders->links() }}</div>

    {{-- Modal (News style) --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-data x-show="true" x-transition.opacity>
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 bg-white z-10">
                    <h3 class="text-lg font-bold text-gray-900">{{ $editingId ? 'Edit' : 'New' }} Official</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
 
                <div class="p-6 space-y-8">
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
 
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                            {{-- Photo Side --}}
                            <div class="space-y-4">
                                <label class="label-badge">Official Photo</label>
                                @if($photo || $photo_url)
                                    <div class="relative group rounded-xl overflow-hidden shadow-sm aspect-square border border-gray-200">
                                        <img src="{{ $photo ? $photo->temporaryUrl() : asset($photo_url) }}" class="w-full h-full object-cover">
                                        <label class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center cursor-pointer">
                                            <span class="text-white text-xs font-bold px-3 py-1 bg-white/20 backdrop-blur-md rounded-lg">Change</span>
                                            <input type="file" wire:model="photo" class="hidden">
                                        </label>
                                    </div>
                                @else
                                    <label class="flex flex-col items-center justify-center w-full aspect-square bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition group">
                                        <svg class="w-10 h-10 text-gray-300 group-hover:scale-110 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                                        <input type="file" wire:model="photo" class="hidden">
                                    </label>
                                @endif
                            </div>
 
                            {{-- Info --}}
                            <div class="md:col-span-3 space-y-6">
                                <div x-show="lang === 'en'" class="space-y-5">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="label-badge">Full Name (EN)</label><input wire:model="name_en" class="admin-input"></div>
                                        <div><label class="label-badge">Position (EN)</label><input wire:model="position_en" class="admin-input"></div>
                                    </div>
                                    <div><label class="label-badge">Bio (EN)</label><textarea wire:model="bio_en" rows="3" class="admin-input"></textarea></div>
                                </div>
                                <div x-show="lang === 'am'" class="space-y-5" style="display:none">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="label-badge">Full Name (AM)</label><input wire:model="name_am" class="admin-input"></div>
                                        <div><label class="label-badge">Position (AM)</label><input wire:model="position_am" class="admin-input"></div>
                                    </div>
                                    <div><label class="label-badge">Bio (AM)</label><textarea wire:model="bio_am" rows="3" class="admin-input"></textarea></div>
                                </div>
                                <div x-show="lang === 'or'" class="space-y-5" style="display:none">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="label-badge">Full Name (OR)</label><input wire:model="name_or" class="admin-input"></div>
                                        <div><label class="label-badge">Position (OR)</label><input wire:model="position_or" class="admin-input"></div>
                                    </div>
                                    <div><label class="label-badge">Bio (OR)</label><textarea wire:model="bio_or" rows="3" class="admin-input"></textarea></div>
                                </div>
                            </div>
                        </div>
                    </div>
 
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-gray-100">
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Hierarchy Settings</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="label-badge">Level</label>
                                    <select wire:model="hierarchy_level" class="admin-input">
                                        <option value="1">Level 1</option>
                                        <option value="2">Level 2</option>
                                        <option value="3">Level 3</option>
                                        <option value="4">Level 4</option>
                                        <option value="5">Level 5</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="label-badge">Reports To</label>
                                    <select wire:model="parent_id" class="admin-input">
                                        <option value="">Top Level</option>
                                        @foreach($potentialParents as $parent)
                                            <option value="{{ $parent->id }}">{{ $parent->name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
 
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Contact Channels</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="label-badge">Email</label><input type="email" wire:model="email" class="admin-input"></div>
                                <div><label class="label-badge">Phone</label><input wire:model="phone" class="admin-input"></div>
                                <div class="col-span-2">
                                    <div x-data="{ lang: 'en' }">
                                        <label class="label-badge">Office / Location</label>
                                        <input wire:model="office_location_en" class="admin-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
 
                <div class="border-t border-gray-100 px-6 py-4 flex justify-end gap-3 sticky bottom-0 bg-white">
                    <button wire:click="$set('showModal', false)"
                        class="px-4 py-2 border border-gray-200 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">Cancel</button>
                    <button wire:click="save"
                        class="px-6 py-2 bg-[#1a56db] text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition">
                        {{ $editingId ? 'Update Official' : 'Register Official' }}
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