<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PatientType: string implements HasLabel
{
    case Cat = 'cat';
    case Dog = 'dog';
    case Rabbit = 'rabbit';
    case Chicken = 'chicken';
    case Squirrel = 'squirrel';
    case Beaver = 'beaver';

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function selectOptions(): array
    {
        return collect(self::cases())
            ->flatMap(fn (PatientType $type) => [$type->value => $type->name])
            ->toArray();
    }

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
