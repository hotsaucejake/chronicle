<?php

namespace App\Filament\Index\Resources;

use App\Filament\Index\Resources\VerbEventResource\Pages;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Thunk\Verbs\Models\VerbEvent;

class VerbEventResource extends Resource
{
    protected static ?string $model = VerbEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $activeNavigationIcon = 'heroicon-s-star';

    protected static ?string $navigationGroup = 'Verbs';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->numeric(thousandsSeparator: '')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->searchable(),
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
            ])
            ->actions([]);
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
            'index' => Pages\ListVerbEvents::route('/'),
        ];
    }
}
