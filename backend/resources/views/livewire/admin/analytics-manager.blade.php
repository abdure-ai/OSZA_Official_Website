<div>
    {{-- ── Header ── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Visitor Analytics</h2>
            <p class="text-sm text-gray-500">Real-time insight into who's visiting your portal</p>
        </div>
        <div class="flex items-center gap-3">
            <select wire:model.live="filterDevice"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                <option value="">All Devices</option>
                <option value="desktop">Desktop</option>
                <option value="mobile">Mobile</option>
                <option value="tablet">Tablet</option>
            </select>
            <select wire:model.live="filterBrowser"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                <option value="">All Browsers</option>
                <option value="Chrome">Chrome</option>
                <option value="Firefox">Firefox</option>
                <option value="Safari">Safari</option>
                <option value="Edge">Edge</option>
                <option value="Opera">Opera</option>
                <option value="Other">Other</option>
            </select>
            <select wire:model.live="range"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#1a56db]">
                <option value="7">Last 7 days</option>
                <option value="30" selected>Last 30 days</option>
                <option value="90">Last 90 days</option>
                <option value="365">Last year</option>
            </select>
        </div>
    </div>

    {{-- ── KPI Cards ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @php
            $kpiCards = [
                ['label' => 'Today', 'value' => $kpis['today'], 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'blue'],
                ['label' => 'This Week', 'value' => $kpis['week'], 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'color' => 'indigo'],
                ['label' => 'This Month', 'value' => $kpis['month'], 'icon' => 'M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'violet'],
                ['label' => 'This Year', 'value' => $kpis['year'], 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'color' => 'amber'],
            ];
            $colorMap = [
                'blue' => ['bg' => 'bg-blue-50', 'icon' => 'text-blue-600', 'text' => 'text-blue-700'],
                'indigo' => ['bg' => 'bg-indigo-50', 'icon' => 'text-indigo-600', 'text' => 'text-indigo-700'],
                'violet' => ['bg' => 'bg-violet-50', 'icon' => 'text-violet-600', 'text' => 'text-violet-700'],
                'amber' => ['bg' => 'bg-amber-50', 'icon' => 'text-amber-600', 'text' => 'text-amber-700'],
            ];
        @endphp
        @foreach($kpiCards as $card)
            @php $c = $colorMap[$card['color']]; @endphp
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center gap-4">
                <div class="w-12 h-12 {{ $c['bg'] }} rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-black text-gray-900">{{ number_format($card['value']) }}</p>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $card['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ── Charts Row ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        {{-- Daily Traffic Line Chart --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-widest">Daily Traffic (Last {{ $range }}
                days)</h3>
            <div id="dailyChart" wire:ignore></div>
        </div>

        {{-- Device Donut --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-widest">Device Breakdown</h3>
            <div id="deviceChart" wire:ignore></div>
            <div class="mt-4 space-y-2">
                @foreach(['Desktop' => '#1a56db', 'Mobile' => '#f5a623', 'Tablet' => '#10b981'] as $label => $color)
                    @php $idx = array_search($label, $deviceLabels);
                        $count = $idx !== false ? $deviceData[$idx] : 0;
                    $total = array_sum($deviceData) ?: 1; @endphp
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background:{{ $color }}"></div>
                            <span class="text-gray-600 font-medium">{{ $label }}</span>
                        </div>
                        <span class="font-bold text-gray-800">{{ number_format($count) }} <span
                                class="text-gray-400 font-normal">({{ round($count / $total * 100) }}%)</span></span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── Hourly Traffic for Today ── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
        <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-widest">Today's Hourly Traffic</h3>
        <div id="hourlyChart" wire:ignore></div>
    </div>

    {{-- ── Two-Column: Top Pages + Browser ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Top Pages --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-widest">Top Pages</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($topPages as $i => $page)
                    @php $maxVal = $topPages->first()->total ?? 1; @endphp
                    <div class="px-6 py-3 flex items-center gap-4">
                        <span class="text-xs font-black text-gray-300 w-4">{{ $i + 1 }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $page->page ?: '/' }}</p>
                            <div class="mt-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#1a56db] rounded-full"
                                    style="width: {{ round($page->total / $maxVal * 100) }}%"></div>
                            </div>
                        </div>
                        <span
                            class="text-sm font-black text-[#1a56db] flex-shrink-0">{{ number_format($page->total) }}</span>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-gray-400 text-sm">No page data yet.</div>
                @endforelse
            </div>
        </div>

        {{-- Browser Breakdown --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-widest">Browser Breakdown</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($browserRaw as $browser => $count)
                    @php $maxBrowser = $browserRaw->max() ?: 1; @endphp
                    <div class="px-6 py-3 flex items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800">{{ $browser ?: 'Unknown' }}</p>
                            <div class="mt-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#f5a623] rounded-full"
                                    style="width: {{ round($count / $maxBrowser * 100) }}%"></div>
                            </div>
                        </div>
                        <span class="text-sm font-black text-gray-700 flex-shrink-0">{{ number_format($count) }}</span>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-gray-400 text-sm">No browser data yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Recent Visitors Table ── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-widest">Recent Visitors</h3>
            <span class="text-xs text-gray-400 font-medium">{{ $recentVisitors->total() }} total in range</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-4 py-3 text-gray-500 font-semibold">IP Address</th>
                        <th class="text-left px-4 py-3 text-gray-500 font-semibold">Location</th>
                        <th class="text-left px-4 py-3 text-gray-500 font-semibold">Page</th>
                        <th class="text-left px-4 py-3 text-gray-500 font-semibold">Device</th>
                        <th class="text-left px-4 py-3 text-gray-500 font-semibold">Browser</th>
                        <th class="text-left px-4 py-3 text-gray-500 font-semibold">Visited At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentVisitors as $log)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $log->ip_address }}</td>
                                        <td class="px-4 py-3">
                                            @if($log->country_code)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-lg" title="{{ $log->country }}">{{ mb_convert_encoding('&#' . (127397 + ord($log->country_code[0])) . ';', 'UTF-8', 'HTML-ENTITIES') }}{{ mb_convert_encoding('&#' . (127397 + ord($log->country_code[1])) . ';', 'UTF-8', 'HTML-ENTITIES') }}</span>
                                                    <div class="flex flex-col">
                                                        <span class="text-xs font-bold text-gray-700 leading-none">{{ $log->city ?: 'Unknown' }}</span>
                                                        <span class="text-[10px] text-gray-400 font-medium">{{ $log->country }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Unknown Location</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 max-w-[180px] truncate">{{ $log->page }}</td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="px-2 py-0.5 rounded text-xs font-semibold
                                                    {{ $log->device === 'mobile' ? 'bg-orange-100 text-orange-700' :
                        ($log->device === 'tablet' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700') }}">
                                                {{ ucfirst($log->device) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 truncate max-w-[100px]">{{ $log->browser }}</td>
                                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $log->visited_at?->diffForHumans() }}</td>
                                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-400">No visitor data yet. Start browsing the
                                public site!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3 border-t border-gray-100">
            {{ $recentVisitors->links() }}
        </div>
    </div>

    {{-- ── ApexCharts ── --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener('livewire:navigated', initCharts);
            document.addEventListener('DOMContentLoaded', initCharts);

            function initCharts() {
                // Daily Line Chart
                const dailyEl = document.querySelector("#dailyChart");
                if (dailyEl && !dailyEl._chart) {
                    dailyEl._chart = new ApexCharts(dailyEl, {
                        chart: { type: 'area', height: 220, toolbar: { show: false }, sparkline: { enabled: false } },
                        series: [{ name: 'Visitors', data: @json($dailyData) }],
                        xaxis: { categories: @json($dailyLabels), labels: { rotate: -30, style: { fontSize: '10px' } } },
                        yaxis: { min: 0, labels: { style: { fontSize: '11px' } } },
                        colors: ['#1a56db'],
                        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.0 } },
                        stroke: { curve: 'smooth', width: 2 },
                        dataLabels: { enabled: false },
                        grid: { borderColor: '#f3f4f6' },
                        tooltip: { theme: 'light' }
                    });
                    dailyEl._chart.render();
                }

                // Device Donut Chart
                const deviceEl = document.querySelector("#deviceChart");
                if (deviceEl && !deviceEl._chart) {
                    deviceEl._chart = new ApexCharts(deviceEl, {
                        chart: { type: 'donut', height: 180 },
                        series: @json($deviceData),
                        labels: @json($deviceLabels),
                        colors: ['#1a56db', '#f5a623', '#10b981'],
                        legend: { show: false },
                        dataLabels: { enabled: false },
                        plotOptions: { pie: { donut: { size: '70%' } } },
                        tooltip: { theme: 'light' }
                    });
                    deviceEl._chart.render();
                }

                // Hourly Bar Chart
                const hourlyEl = document.querySelector("#hourlyChart");
                if (hourlyEl && !hourlyEl._chart) {
                    hourlyEl._chart = new ApexCharts(hourlyEl, {
                        chart: { type: 'bar', height: 160, toolbar: { show: false } },
                        series: [{ name: 'Visitors', data: @json($hourlyData) }],
                        xaxis: { categories: @json($hourlyLabels), labels: { style: { fontSize: '9px' } } },
                        yaxis: { min: 0, labels: { style: { fontSize: '10px' } } },
                        colors: ['#1a56db'],
                        plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
                        dataLabels: { enabled: false },
                        grid: { borderColor: '#f3f4f6' },
                        tooltip: { theme: 'light' }
                    });
                    hourlyEl._chart.render();
                }
            }
        </script>
    @endpush
</div>