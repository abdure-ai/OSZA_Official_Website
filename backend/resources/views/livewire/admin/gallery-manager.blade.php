<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Gallery</h2>
            <p class="text-sm text-gray-500">{{ $items->total() }} total</p>
        </div>
        <button wire:click="openCreate"
            class="flex items-center gap-2 px-4 py-2 bg-[#1a56db] text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>Add Photo
        </button>
    </div>

    <div class="mb-4">
        <select wire:model.live="filterWoreda"
            class="px-4 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:ring-2 focus:ring-[#1a56db] focus:outline-none">
            <option value="">All Woredas</option>
            @foreach($woredas as $w)<option value="{{ $w->id }}">{{ $w->name_en }}</option>@endforeach
        </select>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($items as $item)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden group">
                <div class="h-40 bg-gray-100 relative overflow-hidden">
                    <img src="{{ asset($item->image_url) }}" alt="{{ $item->title }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button wire:click="openEdit({{ $item->id }})"
                            class="bg-white text-blue-600 rounded-lg p-1.5 shadow text-xs hover:bg-blue-50 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button wire:click="delete({{ $item->id }})" wire:confirm="Delete this photo?"
                            class="bg-white text-red-500 rounded-lg p-1.5 shadow text-xs hover:bg-red-50 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-2">
                    <p class="text-xs font-medium text-gray-800 truncate">{{ $item->title ?: 'Untitled' }}</p>
                    <p class="text-xs text-gray-400">{{ $item->category }}</p>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-16 text-gray-400">No photos yet.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $items->links() }}</div>

    @if($showModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-data x-show="true">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg" @click.stop>
                <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">{{ $editingId ? 'Edit' : 'Add' }} Photo</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600"><svg
                            class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg></button>
                </div>
                <div class="p-6 space-y-4">
                    <div><label class="text-sm font-semibold text-gray-700 block mb-1">Image File
                            {{ !$editingId ? '*' : '' }}</label><input type="file" wire:model="image"
                            class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-600">@error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                    <div><label class="text-sm font-semibold text-gray-700 block mb-1">Title</label><input
                            wire:model="title"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-sm font-semibold text-gray-700 block mb-1">Category</label><input
                                wire:model="category"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                        </div>
                        <div><label class="text-sm font-semibold text-gray-700 block mb-1">Woreda</label>
                            <select wire:model="woreda_id"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db] bg-white">
                                <option value="">None (Zone-level)</option>
                                @foreach($woredas as $w)<option value="{{ $w->id }}">{{ $w->name_en }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center gap-2"><input type="checkbox" wire:model="is_active"
                            class="w-4 h-4 text-[#1a56db] rounded border-gray-300"><label
                            class="text-sm text-gray-700 font-medium">Active (show on website)</label></div>
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