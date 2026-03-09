<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">News Articles</h2>
            <p class="text-sm text-gray-500">{{ $news->total() }} total</p>
        </div>
        <button wire:click="openCreate"
            class="flex items-center gap-2 px-4 py-2 bg-[#1a56db] text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Article
        </button>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search articles..."
            class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db] w-64">
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Title</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Category</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Status</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Date</th>
                    <th class="text-right px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($news as $post)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            @if($post->thumbnail_url)
                                <img src="{{ config('app.url') . $post->thumbnail_url }}"
                                    class="w-8 h-8 rounded object-cover inline-block mr-2 align-middle border">
                            @endif
                            <span class="font-medium text-gray-800">{{ Str::limit($post->title_en, 55) }}</span>
                        </td>
                        <td class="px-4 py-3"><span
                                class="bg-blue-50 text-blue-700 text-xs px-2 py-0.5 rounded font-medium">{{ $post->category }}</span>
                        </td>
                        <td class="px-4 py-3"><span
                                class="px-2 py-0.5 rounded text-xs font-semibold {{ $post->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $post->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $post->published_at?->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right flex items-center justify-end gap-2">
                            <button wire:click="openEdit({{ $post->id }})"
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-blue-50 transition">Edit</button>
                            <button wire:click="delete({{ $post->id }})" wire:confirm="Delete this article?"
                                class="text-red-500 hover:text-red-700 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-red-50 transition">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-400">No articles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">{{ $news->links() }}</div>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-data x-show="true"
            x-transition.opacity>
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">{{ $editingId ? 'Edit' : 'New' }} Article</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-5">
                    {{-- Tabs --}}
                    <div class="grid grid-cols-1 gap-4" x-data="{tab:'en'}">
                        <div class="flex gap-2 border-b border-gray-100 pb-2">
                            <button @click="tab='en'"
                                :class="tab==='en' ? 'border-b-2 border-[#1a56db] text-[#1a56db]' : 'text-gray-500'"
                                class="text-sm font-medium pb-2 px-1 transition-colors">English</button>
                            <button @click="tab='am'"
                                :class="tab==='am' ? 'border-b-2 border-[#1a56db] text-[#1a56db]' : 'text-gray-500'"
                                class="text-sm font-medium pb-2 px-1 transition-colors">አማርኛ</button>
                            <button @click="tab='or'"
                                :class="tab==='or' ? 'border-b-2 border-[#1a56db] text-[#1a56db]' : 'text-gray-500'"
                                class="text-sm font-medium pb-2 px-1 transition-colors">Afaan Oromo</button>
                        </div>

                        <div x-show="tab==='en'" class="space-y-4">
                            <div><label class="text-sm font-semibold text-gray-700 block mb-1">Title (EN) *</label><input
                                    wire:model="title_en"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">@error('title_en')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                            <div><label class="text-sm font-semibold text-gray-700 block mb-1">Content (EN)
                                    *</label><textarea wire:model="content_en" rows="8"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db] resize-none"></textarea>
                            </div>
                        </div>
                        <div x-show="tab==='am'" class="space-y-4">
                            <div><label class="text-sm font-semibold text-gray-700 block mb-1">Title (AM)</label><input
                                    wire:model="title_am"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                            </div>
                            <div><label class="text-sm font-semibold text-gray-700 block mb-1">Content (AM)</label><textarea
                                    wire:model="content_am" rows="8"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db] resize-none"></textarea>
                            </div>
                        </div>
                        <div x-show="tab==='or'" class="space-y-4">
                            <div><label class="text-sm font-semibold text-gray-700 block mb-1">Title (OR)</label><input
                                    wire:model="title_or"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                            </div>
                            <div><label class="text-sm font-semibold text-gray-700 block mb-1">Content (OR)</label><textarea
                                    wire:model="content_or" rows="8"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db] resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-sm font-semibold text-gray-700 block mb-1">Category</label><input
                                wire:model="category"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                        </div>
                        <div><label class="text-sm font-semibold text-gray-700 block mb-1">Status</label>
                            <select wire:model="status"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db] bg-white">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                        <div><label class="text-sm font-semibold text-gray-700 block mb-1">Published Date</label><input
                                type="date" wire:model="published_at"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                        </div>
                        <div><label class="text-sm font-semibold text-gray-700 block mb-1">Thumbnail Image</label><input
                                type="file" wire:model="thumbnail"
                                class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-[#1a56db]/10 file:text-[#1a56db] file:font-medium">
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-100 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="$set('showModal', false)"
                        class="px-4 py-2 border border-gray-200 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">Cancel</button>
                    <button wire:click="save"
                        class="px-6 py-2 bg-[#1a56db] text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition">Save
                        Article</button>
                </div>
            </div>
        </div>
    @endif
</div>