<?php

namespace App\Filament\Index\Resources;

use App\Filament\Index\Resources\SpatieDocumentResource\Pages\EditSpatieDocument;
use App\Filament\Index\Resources\SpatieDocumentResource\Pages\ListSpatieDocuments;
use App\Filament\Index\Resources\SpatieDocumentResource\RelationManagers\SnapshotsRelationManager;
use App\Projections\SpatieDocumentProjection;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SpatieDocumentResource extends Resource
{
    protected static ?string $model = SpatieDocumentProjection::class;

    protected static ?string $navigationGroup = 'Spatie';

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $activeNavigationIcon = 'heroicon-s-document-duplicate';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                MarkdownEditor::make('content')
                    ->disableToolbarButtons(['attachFiles'])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
                IconColumn::make('is_locked')
                    ->label('Locked?')
                    ->boolean()
                    ->trueIcon('heroicon-s-lock-closed')
                    ->falseIcon('heroicon-s-lock-open')
                    ->trueColor('danger')
                    ->falseColor('success'),
                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('firstEditUser.username')
                    ->label('First Editor')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('lastEditUser.username')
                    ->label('Last Editor')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('unique_editor_count')
                    ->label('# Editors')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('edit_count')
                    ->label('# Edits')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('last_edited_at')
                    ->label('Last Edited')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make()
                    ->visible(fn (SpatieDocumentProjection $record) => !$record->is_locked),
            ])
            ->recordUrl(fn (SpatieDocumentProjection $record) => $record->is_locked ? null : EditSpatieDocument::getUrl([$record]));
    }

    public static function getRelations(): array
    {
        return [
            SnapshotsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSpatieDocuments::route('/'),
            'edit' => EditSpatieDocument::route('/{record}/edit'),
        ];
    }
}
