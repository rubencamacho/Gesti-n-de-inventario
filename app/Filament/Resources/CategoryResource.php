<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    //Ordenar los elementos de la navegación
    protected static ?int $navigationSort = 10;

    //Creación de grupos
    public static function getNavigationGroup(): string
    {
        return __('Almacén');
    }

    //Sobreesceibir la etiqueta
    public static function getLabel(): string
    {
        return __('Categoría');
    }

    //Sobreescribir el nombre de la opción de menú
    public static function getNavigationLabel(): string
    {
        return __('Categorías');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
