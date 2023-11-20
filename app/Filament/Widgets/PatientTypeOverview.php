<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use App\Enums\PatientType;
use Illuminate\Support\Str;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PatientTypeOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return collect(PatientType::cases())
            ->map(function(PatientType $type) {
               return Stat::make(Str::plural($type->name), Patient::ofType($type)->count());
            })
            ->toArray();
    }
}
