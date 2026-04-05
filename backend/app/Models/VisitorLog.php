<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class VisitorLog extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'page',
        'referrer',
        'device',
        'browser',
        'session_id',
        'locale',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    // ── Scopes ──────────────────────────────────────────────────────────────────

    public function scopeDaily(Builder $query): Builder
    {
        return $query->whereDate('visited_at', today());
    }

    public function scopeWeekly(Builder $query): Builder
    {
        return $query->where('visited_at', '>=', now()->subWeek());
    }

    public function scopeMonthly(Builder $query): Builder
    {
        return $query->where('visited_at', '>=', now()->subMonth());
    }

    public function scopeAnnual(Builder $query): Builder
    {
        return $query->where('visited_at', '>=', now()->subYear());
    }

    public function scopeInRange(Builder $query, string $from, string $to): Builder
    {
        return $query->whereBetween('visited_at', [$from, $to]);
    }
}
