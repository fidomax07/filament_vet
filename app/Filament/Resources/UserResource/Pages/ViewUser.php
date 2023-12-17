<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Support\HtmlString;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;


class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function getHeading(): string|Htmlable
    {
        /** @var User $user */
        $user = $this->record;

        return new HtmlString("<span class='font-normal'>Viewing:</span> $user->name");
    }
}
