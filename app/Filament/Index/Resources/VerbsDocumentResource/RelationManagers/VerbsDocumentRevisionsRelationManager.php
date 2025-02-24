<?php

namespace App\Filament\Index\Resources\VerbsDocumentResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VerbsDocumentRevisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'verbsDocumentRevisions';

    public function form(Form $form): Form
    {
        return $form;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('verbs_document_id')
            ->columns([
                // TextColumn::make('document_id'),
                TextColumn::make('version')
                    ->sortable()
                    ->searchable(),
                // TextColumn::make('content'),
                TextColumn::make('editor.username')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('edited_at')
                    ->label('Edited')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('content')
                    ->label('Content')
                    ->markdown()
                    ->columnSpanFull(),
            ]);
    }
}
