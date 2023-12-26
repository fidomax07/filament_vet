<?php

namespace App\Filament\Resources\PatientResource\Pages;

use Filament\Actions;
use App\Filament\Resources\PatientResource;
use Filament\Resources\Pages\ListRecords;

class ListPatients extends ListRecords
{
    protected static string $resource = PatientResource::class;

    protected ?string $subheading = 'List of all patients';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PatientResource\Widgets\PatientOverview::make([
                'orderBy' => 'max'
            ]),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            PatientResource\Widgets\PatientOverview::make([
                'orderBy' => 'min'
            ]),
        ];
    }
}
