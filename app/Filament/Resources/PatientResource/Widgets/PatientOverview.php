<?php

namespace App\Filament\Resources\PatientResource\Widgets;

use App\Models\Patient;
use App\Enums\PatientType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class PatientOverview extends BaseWidget
{
    public string $orderBy;

    protected function getStats(): array
    {
        return DB::table('patients')
            ->selectRaw('initcap(type) as type, count(*) as count')
            ->groupBy('type')
            ->orderBy('count', $this->orderBy == 'max' ? 'desc' : 'asc')
            ->limit(3)
            ->get()
            ->when(
                $this->orderBy == 'min',
                fn (Collection $coll) => $coll->sortByDesc('count', SORT_NUMERIC)
            )
            ->map(fn ($stat) => Stat::make(Str::plural($stat->type), $stat->count))
            ->toArray();
    }
}
