<?php

namespace App\Filament\Index\Resources;

use App\Filament\Index\Resources\VerbSnapshotResource\Pages;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Thunk\Verbs\Models\VerbSnapshot;

class VerbSnapshotResource extends Resource
{
    protected static ?string $model = VerbSnapshot::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';

    protected static ?string $activeNavigationIcon = 'heroicon-s-camera';

    protected static ?string $navigationGroup = 'Verbs';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->numeric(thousandsSeparator: '')
                    ->sortable(),
                Tables\Columns\TextColumn::make('state_id')
                    ->label('State ID')
                    ->numeric(thousandsSeparator: '')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_event_id')
                    ->label('Last Event ID')
                    ->numeric(thousandsSeparator: '')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListVerbSnapshots::route('/'),
            'create' => Pages\CreateVerbSnapshot::route('/create'),
            'edit' => Pages\EditVerbSnapshot::route('/{record}/edit'),
        ];
    }
}
