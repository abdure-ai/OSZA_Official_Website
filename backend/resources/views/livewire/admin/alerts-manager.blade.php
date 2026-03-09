<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight text-red-900">Emergency Broadcast
                Terminal</h2>
            <p class="text-sm text-gray-500 font-medium tracking-tight">Deploy critical notices and real-time zone
                alerts</p>
        </div>
        <button wire:click="openCreate"
            class="flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-red-700 transition shadow-lg shadow-red-500/20 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>Initiate Alert
        </button>
    </div>

    <div class="bg-white rounded-[2.5rem] border-2 border-gray-50 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/50 border-b-2 border-gray-50">
                <tr>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Broadcast Message</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Threat Level</th>
                    <th class="text-left px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Transmission Status</th>
                    <th class="text-right px-8 py-5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($alerts as $a)
                            <tr class="hover:bg-red-50/20 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="font-bold text-gray-900 group-hover:text-red-600 transition-colors">
                                        {{ Str::limit($a->message_en, 60) }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-[0.2em] border {{ 
                                            $a->severity === 'danger' ? 'bg-red-100 text-red-700 border-red-200' :
                    ($a->severity === 'warning' ? 'bg-amber-100 text-amber-700 border-amber-200' : 'bg-blue-100 text-blue-700 border-blue-200') 
                                        }}">
                                        {{ $a->severity }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="w-2 h-2 rounded-full {{ $a->is_active ? 'bg-red-500 animate-pulse shadow-[0_0_8px_rgba(239,68,68,0.5)]' : 'bg-gray-300' }}"></span>
                                        <span
                                            class="text-[10px] font-black uppercase tracking-widest {{ $a->is_active ? 'text-red-700' : 'text-gray-400' }}">
                                            {{ $a->is_active ? 'Live Broadcast' : 'Standby / Idle' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right space-x-2">
                                    <button wire:click="openEdit({{ $a->id }})"
                                        class="inline-flex items-center justify-center w-10 h-10 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $a->id }})" wire:confirm="Terminate this alert broadcast?"
                                        class="inline-flex items-center justify-center w-10 h-10 bg-red-50 text-red-500 rounded-xl hover:bg-red-600 hover:text-white transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="text-gray-300 font-black uppercase tracking-widest text-xs">No Emergency
                                Signals Detected</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($showModal)
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div
                class="bg-white rounded-[3rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col animate-modal-up">
                <div class="px-10 py-8 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">
                            {{ $editingId ? 'Refine' : 'Deploy' }} Command</h3>
                        <p class="text-[10px] font-black text-red-600 uppercase tracking-[0.3em] mt-1">Incident Management
                            Protocol</p>
                    </div>
                    <button wire:click="$set('showModal', false)"
                        class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-10 space-y-8 overflow-y-auto">
                    <div x-data="{tab:'en'}">
                        <div class="flex gap-4 border-b border-gray-100 mb-8">
                            <button @click="tab='en'"
                                :class="tab==='en' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-400 font-medium'"
                                class="pb-3 px-2 text-[10px] font-black uppercase tracking-widest transition-all">English
                                Broadcast</button>
                            <button @click="tab='am'"
                                :class="tab==='am' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-400 font-medium'"
                                class="pb-3 px-2 text-[10px] font-black uppercase tracking-widest transition-all">አማርኛ
                                ስርጭት</button>
                            <button @click="tab='or'"
                                :class="tab==='or' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-400 font-medium'"
                                class="pb-3 px-2 text-[10px] font-black uppercase tracking-widest transition-all">Afaan
                                Oromoo</button>
                        </div>

                        <div class="space-y-6">
                            <div x-show="tab==='en'">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Transmission
                                    Content (EN)</label>
                                <textarea wire:model="message_en" rows="4"
                                    placeholder="Compose emergency notice in English..."
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-red-600 focus:bg-white rounded-3xl px-6 py-4 text-sm font-medium transition-all resize-none"></textarea>
                            </div>
                            <div x-show="tab==='am'">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Transmission
                                    Content (AM)</label>
                                <textarea wire:model="message_am" rows="4" placeholder="የአደጋ ጊዜ መልዕክት በአማርኛ ይጻፉ..."
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-red-600 focus:bg-white rounded-3xl px-6 py-4 text-sm font-medium transition-all resize-none"></textarea>
                            </div>
                            <div x-show="tab==='or'">
                                <label
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Transmission
                                    Content (OR)</label>
                                <textarea wire:model="message_or" rows="4"
                                    placeholder="Beeksisa hatattamaa Afaan Oromootiin barreessi..."
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-red-600 focus:bg-white rounded-3xl px-6 py-4 text-sm font-medium transition-all resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Threat
                                Severity</label>
                            <select wire:model="severity"
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-red-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm outline-none appearance-none">
                                <option value="info">INFO / GENERAL</option>
                                <option value="warning">WARNING / CAUTION</option>
                                <option value="danger">CRITICAL / DANGER</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Automatic
                                Expiration</label>
                            <input type="datetime-local" wire:model="expires_at"
                                class="w-full bg-gray-50 border-2 border-transparent focus:border-red-600 focus:bg-white rounded-2xl px-6 py-4 font-bold text-sm">
                        </div>

                        <div
                            class="col-span-2 flex items-center justify-between p-6 bg-red-50 rounded-3xl border border-red-100">
                            <div>
                                <h5
                                    class="text-xs font-black text-red-900 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-2 h-2 bg-red-600 rounded-full animate-ping"></span> Live Broadcast Status
                                </h5>
                                <p class="text-[10px] text-red-700 font-bold uppercase tracking-tight mt-1">When
                                    active, alert will be globally visible</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                <div
                                    class="w-14 h-7 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-400 after:border after:rounded-full after:h-5 after:w-6 after:transition-all peer-checked:bg-red-600 shadow-inner">
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="px-10 py-10 bg-gray-50/50 border-t border-gray-50 flex items-center justify-end gap-3">
                    <button wire:click="$set('showModal', false)"
                        class="px-8 py-3 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-red-500 transition">Abort
                        Mission</button>
                    <button wire:click="save"
                        class="px-12 py-4 bg-red-600 text-white rounded-full text-xs font-black uppercase tracking-[0.2em] hover:bg-red-700 transition shadow-xl shadow-red-500/30 active:scale-95">
                        {{ $editingId ? 'Authorize Update' : 'Broadcast Alert' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>