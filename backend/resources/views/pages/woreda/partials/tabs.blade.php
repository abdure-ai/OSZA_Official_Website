{{-- Sub-nav --}}
<div class="bg-white border-b border-gray-100 shadow-sm sticky top-20 z-30">
    <div class="max-w-[1440px] mx-auto px-4 flex gap-1 overflow-x-auto">
        @php
            $tabs = [
                ['route' => 'woreda.show', 'label' => 'Overview'],
                ['route' => 'woreda.about', 'label' => 'About'],
                ['route' => 'woreda.gallery', 'label' => 'Gallery'],
                ['route' => 'woreda.services', 'label' => 'Services'],
                ['route' => 'woreda.contact', 'label' => 'Contact'],
            ];
        @endphp
        @foreach($tabs as $tab)
            <a href="{{ route($tab['route'], $slug) }}"
                class="whitespace-nowrap px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ request()->routeIs($tab['route']) ? 'border-[#1a56db] text-[#1a56db]' : 'border-transparent text-gray-600 hover:text-[#1a56db]' }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>
</div>