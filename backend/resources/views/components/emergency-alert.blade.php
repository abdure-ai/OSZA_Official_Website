@php
    use App\Models\EmergencyAlert;
    $alert = EmergencyAlert::where('is_active', 1)
        ->where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now()); })
        ->orderByDesc('created_at')
        ->first();
    $locale = session('locale', 'en');
@endphp

@if($alert)
    <div class="bg-red-600 text-white text-sm py-2 px-4 flex items-center justify-center gap-2 relative"
        x-data="{ show: true }" x-show="show">
        <svg class="w-4 h-4 flex-shrink-0 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                clip-rule="evenodd" />
        </svg>
        <span class="font-semibold">Emergency Alert:</span>
        <span>{{ $alert->{'message_' . $locale} ?? $alert->message_en }}</span>
        <button @click="show = false" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/70 hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
@endif