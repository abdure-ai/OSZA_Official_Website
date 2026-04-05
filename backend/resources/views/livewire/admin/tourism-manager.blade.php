<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Tourism Sites</h2>
            <p class="text-sm text-gray-500">{{ $sites->total() }} total</p>
        </div>
        <button wire:click="openCreate"
            class="flex items-center gap-2 px-4 py-2 bg-[#1a56db] text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>Add Destination
        </button>
    </div>

    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search destinations..."
            class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db] w-64">
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Destination</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Category</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Woreda</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Status</th>
                    <th class="text-right px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($sites as $site)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($site->cover_image_url)
                                    <img src="{{ asset($site->cover_image_url) }}"
                                        class="w-10 h-10 rounded-lg object-cover border border-gray-100">
                                @endif
                                <div>
                                    <span class="font-medium text-gray-800">{{ $site->name_en }}</span>
                                    <span class="text-gray-400 text-[10px] block">{{ $site->slug }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            <span
                                class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded text-[10px] font-bold uppercase">{{ $site->category ?: 'General' }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $site->woreda?->name_en ?: '—' }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-0.5 rounded text-xs font-semibold {{ $site->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $site->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right flex items-center justify-end gap-2">
                            <a href="{{ route('tourism.show', $site->slug) }}" target="_blank"
                                class="text-gray-400 hover:text-gray-600 text-xs border border-gray-200 rounded-lg px-2 py-1.5 transition">View</a>
                            <button wire:click="openEdit({{ $site->id }})"
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-blue-50 transition">Edit</button>
                            <button wire:click="delete({{ $site->id }})" wire:confirm="Delete this destination?"
                                class="text-red-500 hover:text-red-700 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-red-50 transition">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-400">No destinations found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">{{ $sites->links() }}</div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-data x-show="true"
            x-transition.opacity>
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-y-auto" @click.stop>
                <div
                    class="border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 bg-white z-10">
                    <h3 class="text-lg font-bold text-gray-900">{{ $editingId ? 'Edit' : 'Add' }} Tourism Site</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Basic Info --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-600 block mb-1">Name (EN) *</label>
                            <input wire:model="name_en"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]">
                            @error('name_en') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-600 block mb-1">Name (AM)</label>
                            <input wire:model="name_am"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-600 block mb-1">Name (OR)</label>
                            <input wire:model="name_or"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-600 block mb-1">Slug</label>
                            <input wire:model="slug"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]"
                                placeholder="auto-generated">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-600 block mb-1">Category</label>
                            <select wire:model="category"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]">
                                <option value="">Select Category</option>
                                <option value="Nature">Nature</option>
                                <option value="History">History</option>
                                <option value="Culture">Culture</option>
                                <option value="Park">Park</option>
                                <option value="Resort">Resort</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-600 block mb-1">Woreda</label>
                            <select wire:model="woreda_id"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]">
                                <option value="">Select Woreda</option>
                                @foreach($woredas as $w)
                                    <option value="{{ $w->id }}">{{ $w->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Descriptions --}}
                    <div x-data="{ tab: 'en' }">
                        <div class="flex gap-4 border-b border-gray-100 mb-4">
                            <button @click="tab = 'en'"
                                :class="tab === 'en' ? 'border-[#1a56db] text-[#1a56db]' : 'border-transparent text-gray-400'"
                                class="pb-2 border-b-2 text-xs font-bold uppercase transition">English</button>
                            <button @click="tab = 'am'"
                                :class="tab === 'am' ? 'border-[#1a56db] text-[#1a56db]' : 'border-transparent text-gray-400'"
                                class="pb-2 border-b-2 text-xs font-bold uppercase transition">Amharic</button>
                            <button @click="tab = 'or'"
                                :class="tab === 'or' ? 'border-[#1a56db] text-[#1a56db]' : 'border-transparent text-gray-400'"
                                class="pb-2 border-b-2 text-xs font-bold uppercase transition">Oromo</button>
                        </div>
                        <div x-show="tab === 'en'">
                            <label class="text-xs font-bold text-gray-600 block mb-1">Description (EN) *</label>
                            <textarea wire:model="description_en" rows="5"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]"></textarea>
                            @error('description_en') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div x-show="tab === 'am'">
                            <label class="text-xs font-bold text-gray-600 block mb-1">Description (AM)</label>
                            <textarea wire:model="description_am" rows="5"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]"></textarea>
                        </div>
                        <div x-show="tab === 'or'">
                            <label class="text-xs font-bold text-gray-600 block mb-1">Description (OR)</label>
                            <textarea wire:model="description_or" rows="5"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]"></textarea>
                        </div>
                    </div>

                    {{-- Media --}}
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <label class="text-xs font-bold text-gray-600 block">Cover Image</label>
                            @if($cover_image)
                                <img src="{{ $cover_image->temporaryUrl() }}"
                                    class="w-full h-48 object-cover rounded-xl border-2 border-dashed border-gray-100">
                            @elseif($cover_image_url)
                                <img src="{{ asset($cover_image_url) }}" class="w-full h-48 object-cover rounded-xl border">
                            @endif
                            <input type="file" wire:model="cover_image"
                                class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-blue-50 file:text-blue-600 font-bold">
                        </div>

                        <div class="space-y-4">
                            <label class="text-xs font-bold text-gray-600 block">Gallery Images</label>
                            <div class="grid grid-cols-4 gap-2 mb-2">
                                @foreach($gallery_urls as $idx => $url)
                                    <div class="relative group">
                                        <img src="{{ asset($url) }}" class="w-full h-16 object-cover rounded-lg">
                                        <button wire:click="removeGalleryImage({{ $idx }})"
                                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition shadow-lg">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <input type="file" wire:model="temp_gallery" multiple
                                class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-blue-50 file:text-blue-600 font-bold">
                        </div>
                    </div>

                    {{-- Video Upload --}}
                    <div class="border border-dashed border-gray-200 rounded-2xl p-5 bg-gray-50/50 space-y-3">
                        <div class="flex items-center justify-between">
                            <label
                                class="text-xs font-bold text-gray-600 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Destination Video
                            </label>
                            @if($video_url)
                                <button wire:click="removeVideo" type="button"
                                    class="text-xs text-red-500 hover:text-red-700 font-bold flex items-center gap-1 transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Remove Video
                                </button>
                            @endif
                        </div>

                        {{-- Preview current or newly uploaded video --}}
                        @if($video_file)
                            <video src="{{ $video_file->temporaryUrl() }}"
                                class="w-full max-h-48 rounded-xl object-contain bg-black" controls muted></video>
                            <p class="text-[10px] text-green-600 font-bold">✓ New video ready to upload</p>
                        @elseif($video_url)
                            <video src="{{ asset($video_url) }}" class="w-full max-h-48 rounded-xl object-contain bg-black"
                                controls muted></video>
                        @endif

                        <input type="file" wire:model="video_file" accept="video/mp4,video/webm,video/quicktime"
                            class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-blue-50 file:text-blue-600 file:font-bold">
                        <p class="text-[10px] text-gray-400">Accepted: MP4, WebM, MOV — max 200 MB. This video will appear
                            beside the description on the public detail page.</p>
                        @error('video_file') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    {{-- Location & Settings --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-600 block mb-1">Latitude</label>
                            <input wire:model="latitude" type="number" step="0.00000001"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-600 block mb-1">Longitude</label>
                            <input wire:model="longitude" type="number" step="0.00000001"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]">
                        </div>
                        <div class="flex items-center gap-6 pt-4">
                            <div class="flex items-center gap-2">
                                <label class="text-xs font-bold text-gray-600">Sort</label>
                                <input wire:model="sort_order" type="number"
                                    class="w-16 border border-gray-200 rounded-xl px-3 py-1 text-sm">
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_active"
                                    class="w-4 h-4 text-[#1a56db] rounded border-gray-300">
                                <span class="text-sm font-semibold text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 px-6 py-4 flex justify-end gap-3 sticky bottom-0 bg-white">
                    <button wire:click="$set('showModal', false)"
                        class="px-4 py-2 border border-gray-200 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">Cancel</button>
                    <button wire:click="save"
                        class="px-6 py-2 bg-[#1a56db] text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                        {{ $editingId ? 'Update' : 'Create' }} Destination
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>