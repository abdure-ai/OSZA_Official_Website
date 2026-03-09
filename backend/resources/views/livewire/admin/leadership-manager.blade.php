<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Leadership</h2>
        </div>
        <button wire:click="openCreate"
            class="flex items-center gap-2 px-4 py-2 bg-[#1a56db] text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition"><svg
                class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>Add Leader</button>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        @forelse($leaders as $l)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden group text-center p-5">
                @if($l->photo_url)<img src="{{ config('app.url') . $l->photo_url }}" alt="{{ $l->name_en }}"
                class="w-16 h-16 rounded-full object-cover border-2 border-white shadow mx-auto mb-3">@else<div
                        class="w-16 h-16 rounded-full bg-[#1a56db]/10 flex items-center justify-center mx-auto mb-3 border-2 border-white shadow">
                        <span class="text-[#1a56db] font-bold">{{ strtoupper(substr($l->name_en, 0, 1)) }}</span>
                    </div>@endif
                <p class="font-semibold text-gray-800 text-sm">{{ $l->name_en }}</p>
                <p class="text-xs text-[#1a56db] font-medium mb-3">{{ $l->title_en }}</p>
                <div class="flex items-center justify-center gap-2">
                    <button wire:click="openEdit({{ $l->id }})"
                        class="text-blue-600 hover:text-blue-800 text-xs font-medium px-2 py-1 rounded-lg hover:bg-blue-50 transition">Edit</button>
                    <button wire:click="delete({{ $l->id }})" wire:confirm="Delete?"
                        class="text-red-500 hover:text-red-700 text-xs font-medium px-2 py-1 rounded-lg hover:bg-red-50 transition">Del</button>
                </div>
            </div>
        @empty<div class="col-span-4 text-center py-12 text-gray-400">No leaders added yet.</div>@endforelse
    </div>
    <div class="mt-4">{{ $leaders->links() }}</div>

    @if($showModal)
            <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-data x-show="true">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg" @click.stop>
                    <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">{{ $editingId ? 'Edit' : 'Add' }} Leader</h3>
                        <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600"><svg
                                class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg></button>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-3 gap-3">
                            <div><label class="text-xs font-bold text-gray-600 block mb-1">Name (EN) *</label><input
                                    wire:model="name_en"
                                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">@error('name_en')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div><label class="text-xs font-bold text-gray-600 block mb-1">Name (AM)</label><input
                                    wire:model="name_am"
                                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                            </div>
                            <div><label class="text-xs font-bold text-gray-600 block mb-1">Name (OR)</label><input
                                    wire:model="name_or"
                                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div><label class="text-xs font-bold text-gray-600 block mb-1">Title (EN) *</label><input
                                    wire:model="title_en"
                                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">@error('title_en')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div><label class="text-xs font-bold text-gray-600 block mb-1">Title (AM)</label><input
                                    wire:model="title_am"
                                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                            </div>
                            <div><label class="text-xs font-bold text-gray-600 block mb-1">Title (OR)</label><input
                                    wire:model="title_or"
                                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div><label class="text-xs font-bold text-gray-600 block mb-1">Rank Order</label><input
                                    type="number" wire:model="rank_order"
                                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                            </div>
                        </div>
                        <div><label class="text-xs font-bold text-gray-600 block mb-1">Photo</label><input type="file"
                                wire:model="photo"
                                class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-blue-50 file:text-blue-600">
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-100 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="$set('showModal', false)"
                        class="px-4 py-2 border border-gray-200 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">Cancel</button>
                    <button wire:click="save"
                        class="px-6 py-2 bg-[#1a56db] text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition">Save</button>
                </div>
            </div>
        </div>
    @endif
</div>