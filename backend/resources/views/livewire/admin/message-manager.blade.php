<div>
    <h2 class="text-xl font-bold text-gray-900 mb-2">Admin Message</h2>
    <p class="text-sm text-gray-500 mb-6">This message is shown as a banner on the home page.</p>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-5 max-w-2xl">
        <!-- Multilingual fields moved inside tabs -->
        <div x-data="{tab:'en'}">
            <div class="flex gap-2 border-b border-gray-100 pb-2 mb-4">
                <button @click="tab='en'"
                    :class="tab==='en' ? 'border-b-2 border-[#1a56db] text-[#1a56db]' : 'text-gray-500'"
                    class="text-sm font-medium pb-2 px-1">English</button>
                <button @click="tab='am'"
                    :class="tab==='am' ? 'border-b-2 border-[#1a56db] text-[#1a56db]' : 'text-gray-500'"
                    class="text-sm font-medium pb-2 px-1">አማርኛ</button>
                <button @click="tab='or'"
                    :class="tab==='or' ? 'border-b-2 border-[#1a56db] text-[#1a56db]' : 'text-gray-500'"
                    class="text-sm font-medium pb-2 px-1">Afaan Oromo</button>
            </div>
            <div x-show="tab==='en'" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-sm font-semibold text-gray-700 block mb-1">Name (EN) *</label><input wire:model="name"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                    </div>
                    <div><label class="text-sm font-semibold text-gray-700 block mb-1">Title / Position (EN) *</label><input
                            wire:model="title_position"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                    </div>
                </div>
                <div><label class="text-sm font-semibold text-gray-700 block mb-1">Message (EN) *</label><textarea wire:model="message_en" rows="5"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db] resize-none"></textarea></div>
            </div>
            <div x-show="tab==='am'" class="space-y-4" style="display: none;">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-sm font-semibold text-gray-700 block mb-1">Name (AM)</label><input wire:model="name_am"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                    </div>
                    <div><label class="text-sm font-semibold text-gray-700 block mb-1">Title / Position (AM)</label><input
                            wire:model="title_position_am"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                    </div>
                </div>
                <div><label class="text-sm font-semibold text-gray-700 block mb-1">Message (AM)</label><textarea wire:model="message_am" rows="5"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db] resize-none"></textarea></div>
            </div>
            <div x-show="tab==='or'" class="space-y-4" style="display: none;">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-sm font-semibold text-gray-700 block mb-1">Name (OR)</label><input wire:model="name_or"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                    </div>
                    <div><label class="text-sm font-semibold text-gray-700 block mb-1">Title / Position (OR)</label><input
                            wire:model="title_position_or"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                    </div>
                </div>
                <div><label class="text-sm font-semibold text-gray-700 block mb-1">Message (OR)</label><textarea wire:model="message_or" rows="5"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a56db] resize-none"></textarea></div>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="text-sm font-semibold text-gray-700 block mb-1">Photo</label><input type="file"
                    wire:model="photo" class="w-full text-xs text-gray-500"></div>
            @if($photo_url)
                <div class="mt-2"><img src="{{ asset($photo_url) }}" class="w-20 h-20 rounded-lg object-cover">
            </div> @endif
        </div>
        <div class="flex items-center gap-2"><input type="checkbox" wire:model="is_active"
                class="rounded text-[#1a56db]"><span class="text-sm font-semibold text-gray-700">Active</span></div>
        <div class="pt-2"><button wire:click="save"
                class="px-6 py-2.5 bg-[#1a56db] text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-lg">Save
                Message</button></div>
    </div>
</div>