<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Hero Slides</h2>
        </div>
        <button wire:click="openCreate"
            class="flex items-center gap-2 px-4 py-2 bg-[#1a56db] text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>Add Slide
        </button>
    </div>

    @if(session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="fixed bottom-10 right-10 z-[100] bg-green-600 text-white px-8 py-4 rounded-2xl shadow-2xl flex items-center gap-4 animate-bounce">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-xl font-bold">✓</div>
            <div>
                <div class="font-black uppercase tracking-tight">Success</div>
                <div class="text-sm opacity-90">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    <div class="space-y-3">
        @forelse($slides as $slide)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden flex items-center gap-4 p-4">
                @if($slide->media_url)
                    @if($slide->media_type === 'video')
                        <div
                            class="w-20 h-14 rounded-lg bg-gray-900 flex items-center justify-center flex-shrink-0 border overflow-hidden">
                            <video src="{{ asset($slide->media_url) }}" class="w-full h-full object-cover"
                                muted></video>
                        </div>
                    @else
                        <img src="{{ asset($slide->media_url) }}"
                            class="w-20 h-14 rounded-lg object-cover flex-shrink-0 border">
                    @endif
                @endif
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 truncate">{{ $slide->title_en }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $slide->subtitle_en }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span
                            class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $slide->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $slide->is_active ? 'Active' : 'Hidden' }}</span>
                        <span
                            class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 uppercase">{{ $slide->media_type }}</span>
                        <span
                            class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-50 text-purple-600 uppercase">{{ $slide->page ?? 'home' }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xs text-gray-400">Sort: {{ $slide->sort_order }}</span>
                    <button wire:click="openEdit({{ $slide->id }})"
                        class="text-blue-600 hover:text-blue-800 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-blue-50 transition font-bold uppercase tracking-wider">Edit</button>
                    <button wire:click="delete({{ $slide->id }})" wire:confirm="Delete this slide?"
                        class="text-red-500 hover:text-red-700 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-red-50 transition font-bold uppercase tracking-wider">Delete</button>
                </div>
            </div>
        @empty<div class="text-center py-12 text-gray-400 bg-white rounded-xl border border-gray-100">No slides yet.
        </div>@endforelse
    </div>
    <div class="mt-4">{{ $slides->links() }}</div>

    @if($showModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-data="{ tab: 'en' }"
            x-show="true">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.stop>
                <div
                    class="border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 bg-white z-10">
                    <h3 class="text-lg font-bold text-gray-900">{{ $editingId ? 'Edit' : 'Add' }} Slide</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Tab Navigation -->
                <div class="flex border-b bg-gray-50 px-6">
                    <button @click="tab = 'en'"
                        :class="tab === 'en' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                        class="px-4 py-3 text-xs font-bold uppercase tracking-widest border-b-2 transition-all">English</button>
                    <button @click="tab = 'am'"
                        :class="tab === 'am' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                        class="px-4 py-3 text-xs font-bold uppercase tracking-widest border-b-2 transition-all">አማርኛ</button>
                    <button @click="tab = 'or'"
                        :class="tab === 'or' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                        class="px-4 py-3 text-xs font-bold uppercase tracking-widest border-b-2 transition-all">Oromo</button>
                    <button @click="tab = 'media'"
                        :class="tab === 'media' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                        class="px-4 py-3 text-xs font-bold uppercase tracking-widest border-b-2 transition-all text-red-500">Media
                        & Config</button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- English Tab -->
                    <div x-show="tab === 'en'" class="space-y-4 animate-fade-in">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Title
                                (EN) *</label>
                            <input wire:model="title_en"
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-blue-500 focus:outline-none transition">
                            @error('title_en') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Subtitle
                                (EN)</label>
                            <textarea wire:model="subtitle_en" rows="3"
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2 text-sm focus:border-blue-500 focus:outline-none transition"></textarea>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">CTA
                                Text (EN)</label>
                            <input wire:model="cta_text"
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-1 text-sm focus:border-blue-500 focus:outline-none transition">
                        </div>
                    </div>

                    <!-- Amharic Tab -->
                    <div x-show="tab === 'am'" class="space-y-4 animate-fade-in">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">ርዕስ
                                (አማርኛ)</label>
                            <input wire:model="title_am"
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-blue-500 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">ንዑስ ርዕስ
                                (አማርኛ)</label>
                            <textarea wire:model="subtitle_am" rows="3"
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2 text-sm focus:border-blue-500 focus:outline-none transition"></textarea>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">የአዝራር
                                ጽሑፍ (አማርኛ)</label>
                            <input wire:model="cta_text_am"
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-1 text-sm focus:border-blue-500 focus:outline-none transition">
                        </div>
                    </div>

                    <!-- Oromo Tab -->
                    <div x-show="tab === 'or'" class="space-y-4 animate-fade-in">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Mata
                                Duree (Oromo)</label>
                            <input wire:model="title_or"
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-blue-500 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Ibsa
                                Gabaabaa (Oromo)</label>
                            <textarea wire:model="subtitle_or" rows="3"
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2 text-sm focus:border-blue-500 focus:outline-none transition"></textarea>
                        </div>
                        <div>
                            <label
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Dubbisuu
                                (Oromo)</label>
                            <input wire:model="cta_text_or"
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-1 text-sm focus:border-blue-500 focus:outline-none transition">
                        </div>
                    </div>

                    <!-- Media Tab -->
                    <div x-show="tab === 'media'" class="space-y-4 animate-fade-in">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Media
                                    Type</label>
                                <div class="flex bg-gray-100 p-1 rounded-xl">
                                    <button type="button" @click="$wire.set('media_type', 'image')"
                                        :class="$wire.media_type === 'image' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500'"
                                        class="flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">📸
                                        Image</button>
                                    <button type="button" @click="$wire.set('media_type', 'video')"
                                        :class="$wire.media_type === 'video' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500'"
                                        class="flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">🎥
                                        Video</button>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Target
                                    Page</label>
                                <div class="flex bg-gray-100 p-1 rounded-xl">
                                    <button type="button" @click="$wire.set('page', 'home')"
                                        :class="$wire.page === 'home' ? 'bg-white shadow-sm text-purple-600' : 'text-gray-500'"
                                        class="flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">🏠
                                        Home</button>
                                    <button type="button" @click="$wire.set('page', 'tourism')"
                                        :class="$wire.page === 'tourism' ? 'bg-white shadow-sm text-purple-600' : 'text-gray-500'"
                                        class="flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">🌍
                                        Tourism</button>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Sort
                                    Order</label>
                                <input type="number" wire:model="sort_order"
                                    class="w-full border-2 border-gray-100 rounded-xl px-4 py-2 text-sm focus:border-blue-500 focus:outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Upload
                                New Media (Optional)</label>
                            <input type="file" wire:model="media"
                                class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-blue-50 file:text-blue-600 file:font-bold border-2 border-dashed border-gray-100 p-4 rounded-2xl hover:border-blue-200 transition">
                            @error('media') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p>
                            @enderror
                            <div wire:loading wire:target="media"
                                class="text-xs text-blue-600 mt-1 font-bold animate-pulse">Uploading file...</div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Target
                                URL</label>
                            <input wire:model="cta_url"
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2 text-sm focus:border-blue-500 focus:outline-none transition"
                                placeholder="/about">
                        </div>

                        <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-2xl">
                            <label class="text-xs font-bold text-gray-700 uppercase tracking-widest">Visibility</label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_active"
                                    class="w-5 h-5 text-blue-600 rounded-lg border-gray-300">
                                <span class="text-sm font-bold text-gray-900">Show on Homepage</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 px-6 py-6 flex justify-end gap-3 bg-gray-50/50 rounded-b-2xl">
                    <button wire:click="$set('showModal', false)"
                        class="px-6 py-2 text-xs font-black uppercase tracking-widest text-gray-500 hover:text-gray-900 transition">Cancel</button>
                    <button wire:click="save"
                        class="px-10 py-3 bg-blue-600 text-white rounded-full text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition shadow-xl shadow-blue-500/30">
                        {{ $editingId ? 'Save Changes' : 'Launch Slide' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>