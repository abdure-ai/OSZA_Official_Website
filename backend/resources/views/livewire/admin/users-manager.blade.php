<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight text-blue-900">User Access Control</h2>
            <p class="text-sm text-gray-500 font-medium tracking-tight">Govern administrative privileges and security protocols</p>
        </div>
        <button wire:click="openCreate"
            class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>Provision User
        </button>
    </div>

    <div class="bg-white rounded-[2.5rem] border-2 border-gray-50 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/50 border-b-2 border-gray-50">
                <tr>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Administrative Identity</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Communication Channel</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Commissioned Date</th>
                    <th class="text-right px-8 py-5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                    <tr class="hover:bg-blue-50/20 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center font-black text-sm border-2 border-white shadow-sm transition-transform group-hover:scale-110">
                                    {{ strtoupper(substr($user->name ?: $user->username ?: 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors tracking-tight">
                                        {{ $user->name ?: $user->username }}
                                        @if($user->id === auth()->id())
                                            <span class="ml-2 text-[9px] font-black bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full uppercase tracking-widest">Master Session</span>
                                        @endif
                                    </div>
                                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-0.5">{{ $user->role ?: 'Operator' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-xs font-bold text-gray-500 tracking-tight uppercase">{{ $user->email }}</div>
                        </td>
                        <td class="px-8 py-5 text-gray-400 font-bold text-[10px] uppercase tracking-tighter">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-8 py-5 text-right space-x-2">
                            <button wire:click="openEdit({{ $user->id }})"
                                class="inline-flex items-center justify-center w-10 h-10 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            @if($user->id !== auth()->id())
                                <button wire:click="delete({{ $user->id }})" wire:confirm="Revoke all access for this user?"
                                    class="inline-flex items-center justify-center w-10 h-10 bg-red-50 text-red-500 rounded-xl hover:bg-red-600 hover:text-white transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="text-gray-300 font-black uppercase tracking-widest text-xs">No Authorized Personnel Indexed</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-8 py-6 bg-gray-50/30 border-t border-gray-50">{{ $users->links() }}</div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-md max-h-[90vh] overflow-hidden flex flex-col animate-modal-up">
                <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ $editingId ? 'Modify' : 'Provision' }} Profile</h3>
                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mt-1">Access Configuration Hub</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <div class="p-10 space-y-8 overflow-y-auto">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Professional Full Name</label>
                        <input wire:model="name" placeholder="e.g. Administrator"
                            class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900 shadow-inner">
                        @error('name') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Official Email Address</label>
                        <input type="email" wire:model="email" placeholder="admin@osza.gov.et"
                            class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900 shadow-inner">
                        @error('email') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Credential Security (Password)</label>
                        <input type="password" wire:model="password" placeholder="{{ $editingId ? 'Keep empty to maintain current' : 'Define secure passphrase' }}"
                            class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900 shadow-inner">
                        @error('password') <p class="text-red-500 text-[10px] mt-2 font-black">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Validate Security Passphrase</label>
                        <input type="password" wire:model="password_confirmation" placeholder="Verify security credentials"
                            class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl px-6 py-4 font-bold transition-all text-gray-900 shadow-inner">
                    </div>
                </div>

                <div class="px-10 py-10 bg-gray-50/50 border-t border-gray-50 flex items-center justify-end gap-3">
                    <button wire:click="$set('showModal', false)"
                        class="px-8 py-3 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-red-500 transition">Abort Change</button>
                    <button wire:click="save"
                        class="px-12 py-4 bg-blue-600 text-white rounded-full text-xs font-black uppercase tracking-[0.2em] hover:bg-blue-700 transition shadow-xl shadow-blue-500/30 active:scale-95">
                        {{ $editingId ? 'Authorize Update' : 'Provision Access' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
