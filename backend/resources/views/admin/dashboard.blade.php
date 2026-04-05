@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        @php
            $cards = [
                ['label' => 'News Articles', 'value' => $stats['news'], 'color' => 'blue', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>'],
                ['label' => 'Woredas', 'value' => $stats['woredas'], 'color' => 'green', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>'],
                ['label' => 'Gallery Items', 'value' => $stats['gallery'], 'color' => 'purple', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
                ['label' => 'Messages', 'value' => $stats['messages'], 'color' => 'amber', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
                ['label' => 'Tenders', 'value' => $stats['tenders'], 'color' => 'red', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
                ['label' => 'Vacancies', 'value' => $stats['vacancies'], 'color' => 'indigo', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
                ['label' => 'Documents', 'value' => $stats['documents'], 'color' => 'teal', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>'],
                ['label' => 'Users', 'value' => $stats['users'], 'color' => 'gray', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>'],
            ];
            $colorMap = [
                'blue' => 'bg-blue-50 text-blue-600',
                'green' => 'bg-green-50 text-green-600',
                'purple' => 'bg-purple-50 text-purple-600',
                'amber' => 'bg-amber-50 text-amber-600',
                'red' => 'bg-red-50 text-red-600',
                'indigo' => 'bg-indigo-50 text-indigo-600',
                'teal' => 'bg-teal-50 text-teal-600',
                'gray' => 'bg-gray-100 text-gray-600',
            ];
        @endphp
        @foreach($cards as $card)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="w-10 h-10 rounded-xl {{ $colorMap[$card['color']] ?? 'bg-gray-50 text-gray-600' }} flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $card['icon'] !!}</svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ number_format($card['value']) }}</span>
                </div>
                <p class="text-sm text-gray-500 font-medium">{{ $card['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Daily Traffic --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-widest">Recent Traffic (Last 7 Days)</h3>
            <div id="dailyChart"></div>
        </div>

        {{-- Device Breakdown --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-widest">Device Breakdown</h3>
            <div id="deviceChart"></div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            function renderDashboardCharts() {
                const dailyData = @json($dailyData);
                const deviceData = @json($deviceData);

                // Daily Line Chart
                const dailyEl = document.querySelector("#dailyChart");
                if (dailyEl) {
                    dailyEl.innerHTML = '';
                    new ApexCharts(dailyEl, {
                        chart: { type: 'area', height: 240, toolbar: { show: false } },
                        series: [{ name: 'Visitors', data: dailyData }],
                        xaxis: { categories: @json($dailyLabels), labels: { style: { fontSize: '10px', fontWeight: 600 } } },
                        yaxis: { min: 0, labels: { style: { fontSize: '10px', fontWeight: 600 } } },
                        colors: ['#1a56db'],
                        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.0 } },
                        stroke: { curve: 'smooth', width: 3 },
                        dataLabels: { enabled: false },
                        tooltip: { theme: 'light' }
                    }).render();
                }

                // Device Donut Chart
                const deviceEl = document.querySelector("#deviceChart");
                if (deviceEl) {
                    deviceEl.innerHTML = '';
                    if (deviceData.some(v => v > 0)) {
                        new ApexCharts(deviceEl, {
                            chart: { type: 'donut', height: 240 },
                            series: deviceData,
                            labels: @json($deviceLabels),
                            colors: ['#1a56db', '#f5a623', '#10b981'],
                            legend: { position: 'bottom', fontSize: '11px', fontWeight: 600 },
                            dataLabels: { enabled: false },
                            plotOptions: { pie: { donut: { size: '70%' } } },
                            tooltip: { theme: 'light' }
                        }).render();
                    } else {
                        deviceEl.innerHTML = '<div class="h-full flex items-center justify-center text-gray-300 text-[10px] font-black uppercase tracking-widest">No Traffic Data Yet</div>';
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', () => setTimeout(renderDashboardCharts, 50));
        </script>
    @endpush

    <div class="grid md:grid-cols-2 gap-6">
        {{-- Recent News --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-bold text-gray-900">Recent News</h2>
                <a href="{{ route('admin.news.index') }}" class="text-sm text-[#1a56db] hover:underline">View All →</a>
            </div>
            <div class="space-y-3">
                @forelse($recentNews as $post)
                    <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
                        <div class="w-2 h-2 rounded-full bg-[#1a56db] flex-shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $post->title_en }}</p>
                            <p class="text-xs text-gray-400">{{ $post->created_at?->diffForHumans() ?? 'N/A' }}</p>
                        </div>
                        <span
                            class="text-xs px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-medium">{{ $post->category }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">No news articles yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Messages --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-bold text-gray-900">Recent Messages</h2>
                <a href="{{ route('admin.contact.index') }}" class="text-sm text-[#1a56db] hover:underline">View All →</a>
            </div>
            <div class="space-y-3">
                @forelse($recentMessages as $msg)
                    <div class="flex items-start gap-3 py-2 border-b border-gray-50 last:border-0">
                        <div
                            class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0 text-xs font-bold text-gray-600">
                            {{ strtoupper(substr($msg->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800">{{ $msg->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $msg->message }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $msg->created_at?->diffForHumans() ?? 'N/A' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">No messages yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection