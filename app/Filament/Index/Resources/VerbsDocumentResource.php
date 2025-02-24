<?php

namespace App\Filament\Index\Resources;

use App\Filament\Index\Resources\DocumentResource\Pages\EditDocument;
use App\Filament\Index\Resources\DocumentResource\Pages\ListDocuments;
use App\Filament\Index\Resources\VerbsDocumentResource\RelationManagers\VerbsDocumentRevisionsRelationManager;
use App\Models\VerbsDocument;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VerbsDocumentResource extends Resource
{
    protected static ?string $model = VerbsDocument::class;

    protected static ?string $navigationGroup = 'Verbs';

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $activeNavigationIcon = 'heroicon-s-document-duplicate';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                MarkdownEditor::make('content')
                    ->label(function (VerbsDocument $record) {
                        return $record->exists
                            ? $record->id
                            : '';
                    })
                    ->columnSpanFull()
                    ->disableToolbarButtons([
                        'attachFiles',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->numeric(thousandsSeparator: '')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_locked')
                    ->label('Locked?')
                    ->boolean()
                    ->trueIcon('heroicon-s-lock-closed')
                    ->falseIcon('heroicon-s-lock-open')
                    ->trueColor('danger')
                    ->falseColor('success'),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('firstEditUser.username')
                    ->label('First Editor')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('lastEditUser.username')
                    ->label('Last Editor')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('unique_editor_count')
                    ->label('# Editors')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('edit_count')
                    ->label('# Edits')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_edited_at')
                    ->label('Last Edited')
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
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (VerbsDocument $record) => !$record->is_locked),
            ])
            // TODO: make policy to prevent editing a locked document
            ->recordUrl(fn (VerbsDocument $record) => $record->is_locked ? null : EditDocument::getUrl([$record]));
    }

    public static function getRelations(): array
    {
        return [
            VerbsDocumentRevisionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocuments::route('/'),
            'edit' => EditDocument::route('/{record}/edit'),
            // TODO: make view page
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
