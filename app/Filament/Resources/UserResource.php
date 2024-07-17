<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\GlobalSearch\Actions\Action;
use Filament\Infolists\Components\Section;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('phone')
                    ->maxLength(255),

                Forms\Components\DatePicker::make('date_of_birth'),

                Forms\Components\DateTimePicker::make('email_verified_at'),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255)
                    ->visibleOn('create'),

                Forms\Components\Checkbox::make('is_active')
                    ->columnSpan(2),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->formatStateUsing(fn (string $state): string => "#$state"),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Basic Info')
                    ->id('basic-info')
                    ->description("User's basic information")
                    ->icon('heroicon-s-adjustments-horizontal')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Full name')
                            ->url(function (User $user) {
                                return route(
                                    'filament.app.resources.users.edit',
                                    ['record' => $user->id]
                                );
                            })
                            ->openUrlInNewTab(),
                        Infolists\Components\TextEntry::make('email'),
                        Infolists\Components\TextEntry::make('phone')
                            ->tooltip("User's personal phone number."),
                        Infolists\Components\TextEntry::make('date_of_birth')
                            ->date(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Metadata Info')
                    ->id('metadata-info')
                    ->icon('heroicon-s-document-chart-bar')
                    ->schema([
                        Infolists\Components\TextEntry::make('email_verified_at')
                            ->dateTime(),
                        Infolists\Components\IconEntry::make('is_active')
                            ->boolean(),
                        Infolists\Components\TextEntry::make('created_at')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->dateTime(),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    /**
     * @param User $record
     *
     * @return array|string[]
     */
    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'User ID' => "#$record->id",
            'Email' => $record->email,
        ];
    }

    public static function getGlobalSearchResultActions($record): array
    {
        return [
            Action::make('View')
                ->url(static::getUrl('view', ['record' => $record]), shouldOpenInNewTab: true),
        ];
    }
}
