<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\PatientType;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PatientResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PatientResource\RelationManagers;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::getNameFormField(),
                static::getTypeFormField(),
                static::getDateOfBirthFormField(),
                static::getOwnerFormField(),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                    // Note:
                    // Below transformation would've been necessary if we would not implement
                    // `Filament\Support\Contracts\HasLabel` interface in our `type` enum.
                    //->formatStateUsing(fn(PatientType $state): string => $state->name),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->sortable(),
                Tables\Columns\TextColumn::make('owner.name')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('type')
                    ->options(PatientType::class),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TreatmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'view' => Pages\ViewPatient::route('/{record}'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->latest();
    }

    public static function getNameFormField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('name')
            ->required()
            ->maxLength(255);
    }

    public static function getTypeFormField(): Forms\Components\Select
    {
        return Forms\Components\Select::make('type')
            ->options(PatientType::class)
            ->required();
    }

    public static function getDateOfBirthFormField(): Forms\Components\DatePicker
    {
        return Forms\Components\DatePicker::make('date_of_birth')
            ->required()
            ->maxDate(now());
    }

    public static function getOwnerFormField(): Forms\Components\Select
    {
        return Forms\Components\Select::make('owner_id')
            ->relationship('owner', 'name')
            ->searchable()
            ->preload()
            ->createOptionForm([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('Phone number')
                    ->tel()
                    ->required(),
            ])
            ->required();
    }
}
