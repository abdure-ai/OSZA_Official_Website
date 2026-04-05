<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Leadership hierarchy</h2>
            <p class="text-sm text-gray-500 font-medium">Manage administrators and organize them into an administrative
                organogram.</p>
        </div>
        <button wire:click="openCreate"
            class="px-6 py-3 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition shadow-xl shadow-blue-100 flex items-center gap-2">
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
                class="bg-white rounded-[2.5rem] border border-gray-100 p-6 shadow-sm hover:shadow-2xl transition-all duration-500 group relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 flex gap-2 opacity-0 group-hover:opacity-100 transition">
                    <button wire:click="openEdit({{ $leader->id }})"
                        class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition"><svg
                            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg></button>
                    <button wire:click="delete({{ $leader->id }})" wire:confirm="Remove this official?"
                        class="p-2 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition"><svg
                            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg></button>
                </div>

                <div class="flex items-center gap-5">
                    @if($leader->photo_url)
                        <img src="{{ asset($leader->photo_url) }}"
                            class="w-20 h-20 rounded-2xl object-cover border-4 border-gray-50 flex-shrink-0">
                    @else
                        <div
                            class="w-20 h-20 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-300 font-black text-2xl border-4 border-gray-50">
                            {{ substr($leader->name_en, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <span
                            class="inline-block px-3 py-1 bg-blue-50 text-blue-600 text-[8px] font-black uppercase tracking-widest rounded-full mb-1">Level
                            {{ $leader->hierarchy_level }}</span>
                        <h3 class="font-black text-gray-900 leading-tight">{{ $leader->name_en }}</h3>
                        <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase">{{ $leader->position_en }}</p>
                    </div>
                </div>

                @if($leader->parent)
                    <div class="mt-4 pt-4 border-t border-gray-50 flex items-center gap-2">
                        <span class="text-[8px] font-black text-gray-300 uppercase tracking-widest italic">Reports To:</span>
                        <span class="text-[10px] font-bold text-blue-600">{{ $leader->parent->name_en }}</span>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-gray-50 rounded-[3rem] border border-dashed border-gray-200">
                <p class="text-gray-400 font-medium">No leadership members registered. Start building your organogram.</p>
            </div>
        @endforelse
    </div>
    <div class="mt-8">{{ $leaders->links() }}</div>

    {{-- Edit Modal --}}
    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-y-auto"
                x-data="{ lang: 'en' }">
                <div
                    class="px-10 py-8 border-b border-gray-50 flex items-center justify-between sticky top-0 bg-white/90 backdrop-blur z-10 text-center">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">
                            {{ $editingId ? 'Modify Official' : 'Register New Official' }}</h3>
                        <div class="flex justify-center gap-4 mt-4">
                            <button @click="lang = 'en'"
                                :class="lang === 'en' ? 'text-blue-600 bg-blue-50' : 'text-gray-400'"
                                class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest transition">English
                                View</button>
                            <button @click="lang = 'am'"
                                :class="lang === 'am' ? 'text-blue-600 bg-blue-50' : 'text-gray-400'"
                                class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest transition">Amharic
                                View</button>
                            <button @click="lang = 'or'"
                                :class="lang === 'or' ? 'text-blue-600 bg-blue-50' : 'text-gray-400'"
                                class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest transition">Oromo
                                View</button>
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

                <div class="p-10 space-y-10">
                    {{-- Photo & Basic Info Row --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                        <div class="col-span-1 space-y-4">
                            @if($photo || $photo_url)
                                <div
                                    class="relative group aspect-square rounded-[2rem] overflow-hidden shadow-2xl border-4 border-white">
                                    <img src="{{ $photo ? $photo->temporaryUrl() : asset($photo_url) }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                    <label
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center cursor-pointer">
                                        <span class="text-white text-[10px] font-black uppercase tracking-widest">Change
                                            Photo</span>
                                        <input type="file" wire:model="photo" class="hidden">
                                    </label>
                                </div>
                            @else
                                <label
                                    class="w-full aspect-square bg-gray-50 rounded-[2rem] border-4 border-dashed border-gray-100 flex flex-col items-center justify-center text-gray-300 hover:bg-blue-50 hover:border-blue-100 transition cursor-pointer group">
                                    <svg class="w-12 h-12 group-hover:scale-110 transition duration-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="mt-4 text-[10px] font-black uppercase tracking-widest">Upload Photo</span>
                                    <input type="file" wire:model="photo" class="hidden">
                                </label>
                            @endif
                        </div>

                        <div class="col-span-3 space-y-8">
                            <div x-show="lang === 'en'" class="grid grid-cols-2 gap-6">
                                <div><label class="label-badge">Full Name (EN)</label><input wire:model="name_en"
                                        class="admin-input"></div>
                                <div><label class="label-badge">Official Position (EN)</label><input
                                        wire:model="position_en" class="admin-input"></div>
                                <div class="col-span-2"><label class="label-badge">Bio / Professional Summary
                                        (EN)</label><textarea wire:model="bio_en" rows="3" class="admin-input"></textarea>
                                </div>
                            </div>
                            <div x-show="lang === 'am'" class="grid grid-cols-2 gap-6" style="display:none">
                                <div><label class="label-badge text-blue-600">Full Name (AM)</label><input
                                        wire:model="name_am" class="admin-input"></div>
                                <div><label class="label-badge text-blue-600">Official Position (AM)</label><input
                                        wire:model="position_am" class="admin-input"></div>
                                <div class="col-span-2"><label class="label-badge text-blue-600">Bio (AM)</label><textarea
                                        wire:model="bio_am" rows="3" class="admin-input"></textarea></div>
                            </div>
                            <div x-show="lang === 'or'" class="grid grid-cols-2 gap-6" style="display:none">
                                <div><label class="label-badge text-green-600">Full Name (OR)</label><input
                                        wire:model="name_or" class="admin-input"></div>
                                <div><label class="label-badge text-green-600">Official Position (OR)</label><input
                                        wire:model="position_or" class="admin-input"></div>
                                <div class="col-span-2"><label class="label-badge text-green-600">Bio (OR)</label><textarea
                                        wire:model="bio_or" rows="3" class="admin-input"></textarea></div>
                            </div>
                        </div>
                    </div>

                    {{-- Hierarchy & Contacts Row --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="bg-blue-50/50 rounded-[2.5rem] p-8 space-y-6">
                            <h4
                                class="text-xs font-black text-blue-400 uppercase tracking-widest border-b border-blue-100 pb-2">
                                Administrative hierarchy</h4>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="label-badge">Level (1=Top)</label>
                                    <select wire:model="hierarchy_level" class="admin-input border-transparent">
                                        <option value="1">Level 1 (Top Admin)</option>
                                        <option value="2">Level 2 (Vice Head)</option>
                                        <option value="3">Level 3 (Dept Head)</option>
                                        <option value="4">Level 4 (Director)</option>
                                        <option value="5">Level 5 (Specialist)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="label-badge">Reports To</label>
                                    <select wire:model="parent_id" class="admin-input border-transparent">
                                        <option value="">None (Top Level)</option>
                                        @foreach($potentialParents as $parent)
                                            <option value="{{ $parent->id }}">{{ $parent->name_en }} (Level
                                                {{ $parent->hierarchy_level }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="label-badge">Sort Order</label>
                                <input type="number" wire:model="rank_order" class="admin-input max-w-[100px]">
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-[2.5rem] p-8 space-y-6">
                            <h4
                                class="text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2">
                                Contact Channels</h4>
                            <div class="grid grid-cols-2 gap-6">
                                <div><label class="label-badge">Official Email</label><input type="email" wire:model="email"
                                        class="admin-input"></div>
                                <div><label class="label-badge">Phone / Extension</label><input wire:model="phone"
                                        class="admin-input"></div>
                                <div class="col-span-2">
                                    <div x-show="lang === 'en'"><label class="label-badge">Office / Floor (EN)</label><input
                                            wire:model="office_location_en" class="admin-input"></div>
                                    <div x-show="lang === 'am'" style="display:none"><label class="label-badge">Office
                                            (AM)</label><input wire:model="office_location_am" class="admin-input"></div>
                                    <div x-show="lang === 'or'" style="display:none"><label class="label-badge">Office
                                            (OR)</label><input wire:model="office_location_or" class="admin-input"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-10 py-8 border-t border-gray-50 flex justify-end gap-4 sticky bottom-0 bg-white">
                    <button wire:click="$set('showModal', false)"
                        class="px-8 py-3 text-gray-400 font-bold uppercase tracking-widest text-[10px] hover:text-gray-900 transition">Discard
                        changes</button>
                    <button wire:click="save"
                        class="bg-blue-600 text-white px-12 py-4 rounded-3xl text-sm font-black uppercase tracking-widest hover:bg-blue-700 transition shadow-2xl shadow-blue-100">
                        {{ $editingId ? 'Apply Updates' : 'Confirm Registration' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <style>
        .label-badge {
            @apply text-[11px] font-black text-gray-500 uppercase tracking-widest block mb-2;
        }

        .admin-input {
            @apply w-full bg-white border border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-900 focus:ring-4 focus:ring-blue-100 focus:border-blue-300 transition duration-300 shadow-sm outline-none;
        }
    </style>
</div>