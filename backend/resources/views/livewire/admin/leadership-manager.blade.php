    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight text-left">Leadership Manager</h2>
            <p class="text-sm text-gray-500 font-medium">Manage administrators and organize the institutional hierarchy.</p>
        </div>
        <button wire:click="openCreate"
            class="flex items-center gap-2 px-6 py-3 bg-[#1a56db] text-white rounded-2xl text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>Add New Official
        </button>
    </div>

    {{-- Organogram Preview / List (Woreda Style) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($leaders as $leader)
            <div
                class="bg-white rounded-[2rem] border-2 border-gray-50 p-8 shadow-sm hover:shadow-xl hover:border-blue-100 transition-all duration-500 group relative overflow-hidden">
                <div class="absolute top-6 right-6 flex gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                    <button wire:click="openEdit({{ $leader->id }})"
                        class="p-2.5 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>
                    <button wire:click="delete({{ $leader->id }})" wire:confirm="Remove this official?"
                        class="p-2.5 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>

                <div class="flex items-center gap-6">
                    @if($leader->photo_url)
                        <div class="relative">
                            <img src="{{ asset($leader->photo_url) }}"
                                class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-lg flex-shrink-0 group-hover:scale-110 transition duration-500">
                            <div class="absolute -bottom-2 -right-2 bg-blue-600 text-white text-[8px] font-black px-2 py-1 rounded-lg shadow-lg uppercase tracking-tighter">Lvl {{ $leader->hierarchy_level }}</div>
                        </div>
                    @else
                        <div
                            class="w-20 h-20 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 font-black text-2xl border-2 border-dashed border-gray-100 flex-shrink-0 relative group-hover:bg-blue-50 group-hover:text-blue-200 transition duration-500">
                            {{ substr($leader->name_en, 0, 1) }}
                            <div class="absolute -bottom-2 -right-2 bg-gray-400 text-white text-[8px] font-black px-2 py-1 rounded-lg shadow-lg uppercase tracking-tighter group-hover:bg-blue-600 transition">Lvl {{ $leader->hierarchy_level }}</div>
                        </div>
                    @endif
                    <div>
                        <h3 class="font-black text-gray-900 leading-tight text-lg group-hover:text-blue-600 transition-colors">{{ $leader->name_en }}</h3>
                        <p class="text-[10px] font-black text-gray-400 mt-1 uppercase tracking-widest">{{ $leader->position_en }}</p>
                    </div>
                </div>

                @if($leader->parent)
                    <div class="mt-6 pt-6 border-t border-gray-50 flex items-center justify-between">
                        <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Reporting To</span>
                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-lg">{{ $leader->parent->name_en }}</span>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-gray-50 rounded-[2.5rem] border-2 border-dashed border-gray-100">
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-300">No Officials Registered</div>
            </div>
        @endforelse
    </div>
    <div class="mt-8">{{ $leaders->links() }}</div>

    {{-- Modal (Woreda style) --}}
    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-data="{ tab: 'en' }">
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col" @click.stop x-transition.opacity>
                <div class="border-b border-gray-50 px-8 py-6 flex items-center justify-between bg-white relative">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 tracking-tight">{{ $editingId ? 'Edit' : 'Register' }} Official</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Institutional Leadership & Governance</p>
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
                    {{-- Language Tabs Content --}}
                    <div x-show="['en', 'am', 'or'].includes(tab)" class="space-y-10">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                            {{-- Photo Side --}}
                            <div>
                                <label class="label-badge text-blue-600">Official Photo</label>
                                <div class="bg-gray-50 border-2 border-dashed border-gray-100 rounded-[2.5rem] p-6 text-center">
                                    @if($photo || $photo_url)
                                        <div class="relative group rounded-[2rem] overflow-hidden shadow-xl aspect-square border-4 border-white">
                                            <img src="{{ $photo ? $photo->temporaryUrl() : asset($photo_url) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                            <label class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center cursor-pointer">
                                                <span class="text-white text-[10px] font-black uppercase tracking-widest bg-white/20 backdrop-blur-md px-4 py-2 rounded-xl">Replace</span>
                                                <input type="file" wire:model="photo" class="hidden">
                                            </label>
                                        </div>
                                    @else
                                        <label class="flex flex-col items-center justify-center w-full aspect-square bg-white border-2 border-dashed border-gray-100 rounded-[2rem] cursor-pointer hover:bg-gray-50 transition group">
                                            <svg class="w-10 h-10 text-gray-200 group-hover:scale-110 transition duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                                            <span class="mt-4 text-[8px] font-black text-gray-300 uppercase tracking-widest text-center">Upload Portrait</span>
                                            <input type="file" wire:model="photo" class="hidden">
                                        </label>
                                    @endif
                                    <div wire:loading wire:target="photo" class="mt-4 text-[8px] font-black text-blue-600 animate-pulse uppercase tracking-widest">Uploading...</div>
                                </div>
                            </div>
 
                            {{-- Info --}}
                            <div class="md:col-span-3 space-y-8">
                                <div x-show="tab === 'en'" class="space-y-6">
                                    <div class="grid grid-cols-2 gap-6">
                                        <div><label class="label-badge">Full Name (EN)</label><input wire:model="name_en" class="admin-input" placeholder="Official's Name"></div>
                                        <div><label class="label-badge">Position (EN)</label><input wire:model="position_en" class="admin-input" placeholder="Role / Title"></div>
                                    </div>
                                    <div><label class="label-badge">Biography (EN)</label><textarea wire:model="bio_en" rows="4" class="admin-input resize-none" placeholder="Professional background..."></textarea></div>
                                </div>
                                <div x-show="tab === 'am'" class="space-y-6" style="display:none">
                                    <div class="grid grid-cols-2 gap-6">
                                        <div><label class="label-badge">Full Name (AM)</label><input wire:model="name_am" class="admin-input" placeholder="ስም"></div>
                                        <div><label class="label-badge">Position (AM)</label><input wire:model="position_am" class="admin-input" placeholder="የሥራ መደብ"></div>
                                    </div>
                                    <div><label class="label-badge">Biography (AM)</label><textarea wire:model="bio_am" rows="4" class="admin-input resize-none" placeholder="የህይወት ታሪክ..."></textarea></div>
                                </div>
                                <div x-show="tab === 'or'" class="space-y-6" style="display:none">
                                    <div class="grid grid-cols-2 gap-6">
                                        <div><label class="label-badge">Full Name (OR)</label><input wire:model="name_or" class="admin-input" placeholder="Maqaa Guutuu"></div>
                                        <div><label class="label-badge">Position (OR)</label><input wire:model="position_or" class="admin-input" placeholder="Gargaaraa"></div>
                                    </div>
                                    <div><label class="label-badge">Biography (OR)</label><textarea wire:model="bio_or" rows="4" class="admin-input resize-none" placeholder="Bio..."></textarea></div>
                                </div>
                            </div>
                        </div>
                    </div>
 
                    {{-- Config Tab Content --}}
                    <div x-show="tab === 'config'" class="space-y-8" style="display:none">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="bg-gray-50 border-2 border-gray-100 rounded-[2.5rem] p-10 space-y-6">
                                <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-4">Organizational Hierarchy</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="label-badge">Level</label>
                                        <select wire:model="hierarchy_level" class="admin-input">
                                            <option value="1">Level 1 (Top)</option>
                                            <option value="2">Level 2</option>
                                            <option value="3">Level 3</option>
                                            <option value="4">Level 4</option>
                                            <option value="5">Level 5</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="label-badge">Reports To</label>
                                        <select wire:model="parent_id" class="admin-input">
                                            <option value="">Top Administrator</option>
                                            @foreach($potentialParents as $parent)
                                                <option value="{{ $parent->id }}">{{ $parent->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
 
                            <div class="bg-gray-50 border-2 border-gray-100 rounded-[2.5rem] p-10 space-y-6">
                                <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-4">Contact Gateway</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="label-badge">Email Address</label><input type="email" wire:model="email" class="admin-input" placeholder="official@osz.gov.et"></div>
                                    <div><label class="label-badge">Direct Phone</label><input wire:model="phone" class="admin-input" placeholder="+251..."></div>
                                    <div class="col-span-2">
                                        <label class="label-badge">Office / Location</label>
                                        <input wire:model="office_location_en" class="admin-input" placeholder="Room/Building/District">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
 
                <div class="border-t border-gray-50 px-8 py-8 flex justify-end gap-3 bg-gray-50/30">
                    <button wire:click="$set('showModal', false)" class="px-8 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 transition">Discard</button>
                    <button wire:click="save" class="px-12 py-4 bg-blue-600 text-white rounded-full text-xs font-black uppercase tracking-[0.2em] hover:bg-blue-700 transition shadow-xl shadow-blue-500/30 active:scale-95">
                        {{ $editingId ? 'Push Updates' : 'Launch Official' }}
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
            @apply w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-900 transition-all outline-none;
        }
 
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</div>