<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;

class CreatePatient extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = PatientResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Name & Birthdate')
                ->description('Name and birthdate of the patient')
                ->schema([
                    PatientResource::getNameFormField(),
                    PatientResource::getDateOfBirthFormField(),
                ]),

            Step::make('Type & Owner info')
                ->description('Patient type and owner information')
                ->schema([
                    PatientResource::getTypeFormField(),
                    PatientResource::getOwnerFormField(),
                ])
        ];
    }

    public function hasSkippableSteps(): bool
    {
        return false;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Patient created successfully';
    }
}
