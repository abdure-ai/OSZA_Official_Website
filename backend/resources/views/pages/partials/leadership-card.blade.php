@php
    $locale = session('locale', 'en');
    $isLarge = $large ?? false;
@endphp

<div class="group relative flex flex-col items-center text-center" 
     x-data="{ showContact: false }" 
     @mouseenter="showContact = true" 
     @mouseleave="showContact = false">
    
    {{-- Card Body --}}
    <div class="relative {{ $isLarge ? 'w-64 h-64' : 'w-48 h-48' }} rounded-[2.5rem] overflow-hidden shadow-2xl mb-6 grayscale group-hover:grayscale-0 transition-all duration-700 border-4 {{ $isLarge ? 'border-blue-600' : 'border-gray-800 group-hover:border-blue-500' }} bg-gray-800">
        @if($leader->photo_url)
            <img src="{{ asset($leader->photo_url) }}" alt="{{ $leader->name_en }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-blue-400">
                <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
            </div>
        @endif

        {{-- Contact Overlay (Hover Effect) --}}
        <div x-show="showContact" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4"
             class="absolute inset-0 bg-blue-900/90 backdrop-blur-sm p-6 flex flex-col justify-center items-center text-white z-20">
            
            <div class="space-y-4 text-center">
                <div class="space-y-1">
                    <p class="text-[9px] font-black text-blue-300 uppercase tracking-[0.2em]">Email</p>
                    <p class="text-[11px] font-bold break-all">{{ $leader->email ?: 'N/A' }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[9px] font-black text-blue-300 uppercase tracking-[0.2em]">Phone</p>
                    <p class="text-xs font-black">{{ $leader->phone ?: 'N/A' }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[9px] font-black text-blue-300 uppercase tracking-[0.2em]">Office</p>
                    <p class="text-[10px] font-bold leading-tight">{{ $leader->{'office_location_'.$locale} ?? $leader->office_location_en ?: 'Main Administration' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Identification --}}
    <h3 class="font-black text-white {{ $isLarge ? 'text-xl' : 'text-base' }} tracking-tight leading-tight uppercase max-w-[200px]">
        {{ $leader->{'name_' . $locale} ?? $leader->name_en }}
    </h3>
    <p class="text-[9px] font-black {{ $isLarge ? 'text-blue-400 bg-blue-900/50' : 'text-gray-400 bg-gray-800' }} uppercase tracking-widest mt-2 px-4 py-1.5 rounded-full">
        {{ $leader->{'position_' . $locale} ?? $leader->position_en }}
    </p>
</div>
