<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Directory</h2>
            <p class="text-sm text-gray-500">{{ $records->total() }} total</p>
        </div>
        <button wire:click="openCreate"
            class="flex items-center gap-2 px-4 py-2 bg-[#1a56db] text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition"><svg
                class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>New Record</button>
    </div>
    <div class="mb-4"><input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name..."
            class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-[#1a56db] w-64"></div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Name</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Position</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Department</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Status</th>
                    <th class="text-right px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($records as $r)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $r->name_en }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $r->position_en }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $r->department_en ?: '—' }}</td>
                        <td class="px-4 py-3"><span
                                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-widest {{ $r->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $r->is_active ? 'Active' : 'Draft' }}</span>
                        </td>
                        <td class="px-4 py-3 text-right flex items-center justify-end gap-2">
                            <button wire:click="openEdit({{ $r->id }})"
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-blue-50 transition">Edit</button>
                            <button wire:click="delete({{ $r->id }})" wire:confirm="Delete?"
                                class="text-red-500 hover:text-red-700 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-red-50 transition">Delete</button>
                        </td>
                    </tr>
                @empty<tr>
                    <td colspan="4" class="text-center py-12 text-gray-400">No records found.</td>
                </tr>@endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">{{ $records->links() }}</div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">{{ $editingId ? 'Edit' : 'New' }} Record</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600"><svg
                            class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg></button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-xs font-bold text-gray-400 block mb-1">Name (EN) *</label><input
                                wire:model="name_en"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-[#1a56db]">
                        </div>
                        <div><label class="text-xs font-bold text-gray-400 block mb-1">Position (EN) *</label><input
                                wire:model="position_en"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-[#1a56db]">
                        </div>
                    </div>
                    <div><label class="text-xs font-bold text-gray-400 block mb-1">Department (EN)</label><input
                            wire:model="department_en" class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-xs font-bold text-gray-400 block mb-1">Phone</label><input
                                wire:model="phone" class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm"></div>
                        <div><label class="text-xs font-bold text-gray-400 block mb-1">Email</label><input
                                wire:model="email" class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-xs font-bold text-gray-400 block mb-1">Office Location</label><input
                                wire:model="office_location"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm"></div>
                        <div><label class="text-xs font-bold text-gray-400 block mb-1">Woreda</label>
                            <select wire:model="woreda_id"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm bg-white">
                                <option value="">None (Zone-level)</option>
                                @foreach($woredas as $w)<option value="{{ $w->id }}">{{ $w->name_en }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-xs font-bold text-gray-400 block mb-1">Photo</label><input type="file"
                                wire:model="photo" class="w-full text-xs text-gray-500"></div>
                        <div class="flex items-center pt-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_active"
                                    class="w-4 h-4 text-[#1a56db] rounded border-gray-300 focus:ring-[#1a56db]">
                                <span class="text-sm font-semibold text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-100 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="$set('showModal', false)"
                        class="px-4 py-2 border border-gray-200 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">Cancel</button>
                    <button wire:click="save"
                        class="px-6 py-2 bg-[#1a56db] text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition">Save
                        Record</button>
                </div>
            </div>
        </div>
    @endif
</div>