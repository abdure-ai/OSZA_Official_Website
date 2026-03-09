<div>
    <h2 class="text-xl font-bold text-gray-900 mb-6">Contact Messages</h2>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Name</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Email</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Subject</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Message</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold">Date</th>
                    <th class="text-right px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($messages as $msg)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $msg->name }}</td>
                        <td class="px-4 py-3 text-gray-500"><a href="mailto:{{ $msg->email }}"
                                class="hover:text-[#1a56db]">{{ $msg->email }}</a></td>
                        <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ $msg->subject ?: '—' }}</td>
                        <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ Str::limit($msg->message, 80) }}</td>
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $msg->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button wire:click="viewMessage({{ $msg->id }})"
                                class="text-[#1a56db] hover:text-blue-800 text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-blue-50 transition border border-transparent hover:border-blue-100">View</button>
                            <button wire:click="delete({{ $msg->id }})" wire:confirm="Delete this message?"
                                class="text-red-500 hover:text-red-700 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-red-50 transition border border-transparent hover:border-red-100">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-400">No messages.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-4 border-t border-gray-100 bg-gray-50/50">{{ $messages->links() }}</div>
    </div>

    {{-- View Message Modal --}}
    @if($viewingMessage)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeView"></div>
            <div
                class="relative bg-white rounded-3xl w-full max-w-2xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden transform transition-all">

                {{-- Header --}}
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 tracking-tight">Message Detail</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">
                            {{ $viewingMessage->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <button wire:click="closeView"
                        class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-200 transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-8 overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-2 gap-6 mb-8 bg-blue-50/50 p-6 rounded-2xl border border-blue-100/50">
                        <div>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-[#1a56db] mb-1">Sender
                                Name</span>
                            <span class="font-bold text-gray-900 text-sm">{{ $viewingMessage->name }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-[#1a56db] mb-1">Email
                                Address</span>
                            <a href="mailto:{{ $viewingMessage->email }}"
                                class="font-bold text-blue-600 hover:text-blue-800 transition-colors text-sm break-all">{{ $viewingMessage->email }}</a>
                        </div>
                        <div class="col-span-2">
                            <span
                                class="block text-[10px] font-black uppercase tracking-widest text-[#1a56db] mb-1">Subject</span>
                            <span
                                class="font-bold text-gray-900 text-sm">{{ $viewingMessage->subject ?: 'No Subject Provided' }}</span>
                        </div>
                    </div>

                    <div>
                        <span
                            class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3 border-b border-gray-100 pb-2">Full
                            Message Content</span>
                        <div class="prose prose-sm text-gray-700 max-w-none font-medium leading-relaxed">
                            {!! nl2br(e($viewingMessage->message)) !!}
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-8 py-5 border-t border-gray-100 bg-gray-50 flex justify-end gap-3 rounded-b-3xl">
                    <a href="mailto:{{ $viewingMessage->email }}"
                        class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gray-50 transition-colors shadow-sm">Reply
                        via Email</a>
                    <button wire:click="closeView"
                        class="px-6 py-2.5 bg-[#1a56db] text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-800 transition-colors shadow-lg shadow-blue-500/20">Close
                        Panel</button>
                </div>

            </div>
        </div>
    @endif
</div>