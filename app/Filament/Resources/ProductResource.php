<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\FormsComponent;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

       //Ordenar los elementos de la navegación
    protected static ?int $navigationSort = 20;

    //Creación de grupos
    public static function getNavigationGroup(): string
    {
        return __('Almacén');
    }

    //Sobreesceibir la etiqueta
    public static function getLabel(): string
    {
        return __('Producto');
    }

    //Sobreescribir el nombre de la opción de menú
    public static function getNavigationLabel(): string
    {
        return __('Productos');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('image')
                    ->label(__('Imagen'))
                    ->image()
                    ->maxSize(4096)
                    ->placeholder(__('Subir imagen del producto'))
                    ->columnSpanFull(),
                //Alinear los campos en columnas
                Grid::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Nombre'))
                            ->required()
                            ->autofocus()
                            ->minLength(2)
                            ->maxLength(200)
                            ->unique(static::getModel(), 'name', ignoreRecord: true)    //Crrear sin repetidos pero ignorando el registro actual
                            ->columns(1)
                            ->placeholder(__('Nombre del producto')),
                        TextInput::make('price')
                            ->label(__('Precio'))
                            ->required()
                            ->type('number')
                            ->minLength(2)
                            ->maxLength(200)
                            ->step('0.01')
                            ->columns(1)
                            ->placeholder(__('Precio del producto')),
                        Select::make('category_id')
                            ->label(__('Categoría'))
                            ->relationship('category', 'name')
                            // ->options(
                            //     \App\Models\Category::all()->pluck('name', 'id')
                            // )
                            ->required()
                            ->columns(1)
                            ->searchable()
                            ->placeholder(__('Seleccionar categoría')),

                    ])->columns(3),

                Textarea::make('description')
                    ->label(__('Descripción'))
                    ->rows(2)
                    ->minLength(2)
                    ->maxLength(200)
                    ->columnSpanFull()
                    ->placeholder(__('Descripción del producto')),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
