<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                TextInput::make('name')
                    ->label(__('Nombre'))
                    ->required()
                    ->autofocus()
                    ->minLength(2)
                    ->maxLength(200)
                    ->unique(static::getModel(), 'name', ignoreRecord: true)    //Crrear sin repetidos pero ignorando el registro actual
                    ->columnSpanFull()
                    ->placeholder(__('Nombre de la categoría')),
                Textarea::make('description')
                    ->label(__('Descripción'))
                    ->rows(2)
                    ->columnSpanFull()
                    ->placeholder(__('Descripción de la categoría')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Nombre'))
                    ->searchable()
                    ->sortable()
                    ->description(fn (Category $category) => $category->description),
                
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
