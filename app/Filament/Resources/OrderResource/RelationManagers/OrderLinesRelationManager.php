<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OrderLinesRelationManager extends RelationManager
{
    protected static string $relationship = 'orderLines';

    //Título de la relación
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Líneas del pedido :id', ['id' => $ownerRecord->id]);
    }

    //Modificar el texto de los botones
    protected static function getRecordLabel(): ?string
    {
        return __('Línea de pedido');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])

            //Añadimos el botón de crear categorías en el listado si no hay registros
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
