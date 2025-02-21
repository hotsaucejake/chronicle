<?php

namespace App\Filament\Index\Resources\DocumentResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RevisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'revisions';

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
                TextColumn::make('version'),
                // TextColumn::make('content'),
                TextColumn::make('editor.username'),
                TextColumn::make('edited_at')
                    ->label('Edited')
                    ->dateTime(),
            ])
            ->filters([
                //
            ]);
    }
}
