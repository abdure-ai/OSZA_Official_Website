{{-- Woreda Hero --}}
<section class="relative h-[40vh] md:h-[50vh] flex items-end overflow-hidden">
    {{-- Background --}}
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent z-10"></div>
        @if($woreda->banner_url)
            <img src="{{ config('app.url') . $woreda->banner_url }}" alt="{{ $woreda->name_en }}"
                class="w-full h-full object-cover scale-105">
        @else
            <div class="w-full h-full bg-[#1a56db]"></div>
        @endif
    </div>

    <div
        class="relative z-20 max-w-[1440px] mx-auto px-4 pb-12 w-full flex flex-col md:flex-row items-start md:items-end gap-6 text-white">
        @if($woreda->logo_url)
            <img src="{{ config('app.url') . $woreda->logo_url }}" alt="{{ $woreda->name_en }}"
                class="w-24 h-24 rounded-3xl object-cover border-4 border-white shadow-2xl flex-shrink-0 bg-white">
        @endif
        <div class="flex-grow">
            <span
                class="inline-block px-3 py-1 bg-[#f5a623] text-blue-900 text-[10px] font-black uppercase tracking-widest rounded-full mb-3 shadow-lg">
                District Portal
            </span>
            <h1
                class="text-4xl md:text-7xl font-black mb-2 leading-none antialiased drop-shadow-2xl italic tracking-tight">
                {{ $woreda->{'name_' . $locale} ?? $woreda->name_en }}
            </h1>
            <p class="text-gray-300 font-bold tracking-widest uppercase text-xs">Official Portal • Oromo Special Zone
            </p>
        </div>
    </div>
</section>