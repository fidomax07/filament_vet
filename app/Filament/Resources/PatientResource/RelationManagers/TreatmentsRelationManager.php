<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use App\Models\Patient;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Treatment;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\PatientResource\Pages\EditPatient;

class TreatmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'treatments';

    protected static ?string $title = 'Patient\'s treatments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan('full'),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpan('full'),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('€')
                    ->minValue(0)
                    ->maxValue(42949672.95),
            ]);
    }

    /**
     * @throws \Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    // Adding notes of the treatment as additional description.
                    ->description(fn (Treatment $t): ?string => $t->notes)
                    ->words(3),
                Tables\Columns\TextColumn::make('price')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\Action::make('Edit owning patient')
                    ->url(EditPatient::getUrl(['record' => $this->getOwnerRecord()])),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $query
                    ->withoutGlobalScopes([SoftDeletingScope::class])
                    ->latest();
            });
    }

    /**
     * Check if the object is read-only.
     *
     * @return bool True if the object is read-only, false otherwise.
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * @param Model|Patient $ownerRecord
     * @param string $pageClass
     * @return bool
     *
     * @noinspection PhpDocSignatureInspection
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->is_approved;
    }
}
