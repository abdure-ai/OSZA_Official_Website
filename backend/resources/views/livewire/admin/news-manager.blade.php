<div x-data="{ showToast: false, toastMessage: '', toastType: 'success' }" 
     @notify.window="showToast = true; toastMessage = $event.detail.message; toastType = $event.detail.type; setTimeout(() => showToast = false, 3000)">
    
    {{-- Toast Notification --}}
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave-end="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
         class="fixed top-5 right-5 z-[100] w-full max-w-sm overflow-hidden bg-white rounded-lg shadow-xl ring-1 ring-black ring-opacity-5"
         style="display: none;">
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <template x-if="toastType === 'success'">
                        <svg class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>
                    <template x-if="toastType === 'error'">
                        <svg class="w-6 h-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </template>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p x-text="toastMessage" class="text-sm font-medium text-gray-900"></p>
                </div>
                <div class="ml-4 flex flex-shrink-0">
                    <button @click="showToast = false" type="button" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                            <div class="flex items-center space-x-3">
                                @if($post->thumbnail_url)
                                    <img src="{{ asset($post->thumbnail_url) }}"
                                        class="w-10 h-10 rounded-lg object-cover border border-gray-100 shadow-sm flex-shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200 flex-shrink-0">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <span class="font-medium text-gray-800 leading-snug">{{ Str::limit($post->title_en, 55) }}</span>
                            </div>
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
                            <div wire:ignore x-data="{
                                content: @entangle('content_en'),
                                init() {
                                    let q = new Quill($refs.editor, { theme: 'snow', placeholder: 'Write article content here...', modules: { toolbar: [ ['bold', 'italic', 'underline'], [{ 'header': [1, 2, 3, false] }], [{ 'list': 'ordered'}, { 'list': 'bullet' }], ['link', 'clean'] ] } });
                                    q.on('text-change', () => { this.content = q.root.innerHTML });
                                    $watch('content', val => { if(val !== q.root.innerHTML) q.root.innerHTML = val || '' });
                                    setTimeout(() => { q.root.innerHTML = this.content || '' }, 100);
                                }
                            }">
                                <label class="text-sm font-semibold text-gray-700 block mb-1">Content (EN) *</label>
                                <div x-ref="editor" class="bg-white min-h-[250px] text-gray-700"></div>
                            </div>
                        </div>
                        <div x-show="tab==='am'" class="space-y-4">
                            <div><label class="text-sm font-semibold text-gray-700 block mb-1">Title (AM)</label><input
                                    wire:model="title_am"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                            </div>
                            <div wire:ignore x-data="{
                                content: @entangle('content_am'),
                                init() {
                                    let q = new Quill($refs.editor, { theme: 'snow', placeholder: 'የጽሁፉን ይዘት እዚህ ያስገቡ...', modules: { toolbar: [ ['bold', 'italic', 'underline'], [{ 'header': [1, 2, 3, false] }], [{ 'list': 'ordered'}, { 'list': 'bullet' }], ['link', 'clean'] ] } });
                                    q.on('text-change', () => { this.content = q.root.innerHTML });
                                    $watch('content', val => { if(val !== q.root.innerHTML) q.root.innerHTML = val || '' });
                                    setTimeout(() => { q.root.innerHTML = this.content || '' }, 100);
                                }
                            }">
                                <label class="text-sm font-semibold text-gray-700 block mb-1">Content (AM)</label>
                                <div x-ref="editor" class="bg-white min-h-[250px] text-gray-700" style="font-family: 'Noto Sans Ethiopic', sans-serif;"></div>
                            </div>
                        </div>
                        <div x-show="tab==='or'" class="space-y-4">
                            <div><label class="text-sm font-semibold text-gray-700 block mb-1">Title (OR)</label><input
                                    wire:model="title_or"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                            </div>
                            <div wire:ignore x-data="{
                                content: @entangle('content_or'),
                                init() {
                                    let q = new Quill($refs.editor, { theme: 'snow', placeholder: 'Qabiyyee barreeffamaa asitti barreessi...', modules: { toolbar: [ ['bold', 'italic', 'underline'], [{ 'header': [1, 2, 3, false] }], [{ 'list': 'ordered'}, { 'list': 'bullet' }], ['link', 'clean'] ] } });
                                    q.on('text-change', () => { this.content = q.root.innerHTML });
                                    $watch('content', val => { if(val !== q.root.innerHTML) q.root.innerHTML = val || '' });
                                    setTimeout(() => { q.root.innerHTML = this.content || '' }, 100);
                                }
                            }">
                                <label class="text-sm font-semibold text-gray-700 block mb-1">Content (OR)</label>
                                <div x-ref="editor" class="bg-white min-h-[250px] text-gray-700"></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-sm font-semibold text-gray-700 block mb-1">Category</label>
                            <select wire:model="category"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db] bg-white">
                                <option value="">Select Category</option>
                                <option value="news">News</option>
                                <option value="press_release">Press Release</option>
                                <option value="update">Update</option>
                            </select>
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
        </div>
    @endif
</div>