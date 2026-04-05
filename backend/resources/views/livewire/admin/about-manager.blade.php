<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">About Page Sections</h2>
            <p class="text-sm text-gray-500">Manage History, Mission, Vision, and other content blocks</p>
        </div>
        <button wire:click="openCreate"
            class="flex items-center gap-2 px-4 py-2 bg-[#1a56db] text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>Add New Section
        </button>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold uppercase tracking-wider">Type</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold uppercase tracking-wider">Title (EN)</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold uppercase tracking-wider">Order</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold uppercase tracking-wider">Status</th>
                    <th class="text-right px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($sections as $section)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-bold uppercase text-[10px] text-blue-600 tracking-widest">
                            {{ $section->type }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $section->title_en }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $section->sort_order }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-0.5 rounded text-xs font-semibold {{ $section->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $section->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right flex items-center justify-end gap-2">
                            <button wire:click="openEdit({{ $section->id }})"
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-blue-50 transition">Edit</button>
                            <button wire:click="delete({{ $section->id }})" wire:confirm="Delete this section?"
                                class="text-red-500 hover:text-red-700 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-red-50 transition">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-400">No sections found. Add one to get started.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">{{ $sections->links() }}</div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                <div
                    class="border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 bg-white z-10">
                    <h3 class="text-lg font-bold text-gray-900">{{ $editingId ? 'Edit' : 'Add' }} About Section</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-600 block mb-1 uppercase tracking-widest">Type
                                *</label>
                            <select wire:model="type"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]">
                                <option value="history">Historical Background</option>
                                <option value="mission">Mission</option>
                                <option value="vision">Vision</option>
                                <option value="general">General Information</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-600 block mb-1 uppercase tracking-widest">Icon / Badge
                                Text</label>
                            <input wire:model="icon"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]"
                                placeholder="e.g. mission, vision, history">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-600 block mb-1 uppercase tracking-widest">Title (EN)
                                *</label>
                            <input wire:model="title_en"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-600 block mb-1 uppercase tracking-widest">Title
                                (AM)</label>
                            <input wire:model="title_am"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-600 block mb-1 uppercase tracking-widest">Title
                                (OR)</label>
                            <input wire:model="title_or"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]">
                        </div>
                    </div>

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
                            <label class="text-xs font-bold text-gray-600 block mb-1 uppercase tracking-widest">Content (EN)
                                *</label>
                            <textarea wire:model="content_en" rows="6"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]"></textarea>
                        </div>
                        <div x-show="tab === 'am'">
                            <label class="text-xs font-bold text-gray-600 block mb-1 uppercase tracking-widest">Content
                                (AM)</label>
                            <textarea wire:model="content_am" rows="6"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]"></textarea>
                        </div>
                        <div x-show="tab === 'or'">
                            <label class="text-xs font-bold text-gray-600 block mb-1 uppercase tracking-widest">Content
                                (OR)</label>
                            <textarea wire:model="content_or" rows="6"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#1a56db]"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <label class="text-xs font-bold text-gray-600 block uppercase tracking-widest">Background Image
                                (Optional)</label>
                            @if($image)
                                <img src="{{ $image->temporaryUrl() }}"
                                    class="w-full h-48 object-cover rounded-xl border-2 border-dashed border-blue-100">
                            @elseif($image_url)
                                <img src="{{ asset($image_url) }}"
                                    class="w-full h-48 object-cover rounded-xl border border-gray-100">
                            @endif
                            <input type="file" wire:model="image"
                                class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-blue-50 file:text-blue-600 font-bold transition file:cursor-pointer">
                        </div>
                        <div class="flex flex-col justify-end space-y-4">
                            <div class="flex items-center gap-6">
                                <div class="flex items-center gap-2">
                                    <label class="text-xs font-bold text-gray-600 uppercase tracking-widest">Sort
                                        Order</label>
                                    <input wire:model="sort_order" type="number"
                                        class="w-20 border border-gray-200 rounded-xl px-3 py-1 text-sm focus:ring-2 focus:ring-[#1a56db]">
                                </div>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="is_active"
                                        class="w-4 h-4 text-[#1a56db] rounded border-gray-300 focus:ring-[#1a56db]">
                                    <span class="text-sm font-semibold text-gray-700">Section Active</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 px-6 py-4 flex justify-end gap-3 sticky bottom-0 bg-white">
                    <button wire:click="$set('showModal', false)"
                        class="px-4 py-2 border border-gray-200 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">Cancel</button>
                    <button wire:click="save"
                        class="px-6 py-2 bg-[#1a56db] text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                        {{ $editingId ? 'Update' : 'Create' }} Section
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>