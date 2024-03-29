<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\FormsComponent;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Infolists\Components\Section;



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
                ImageColumn::make('image')
                    ->label(__('Imagen')),
                TextColumn::make('name')
                    ->label(__('Nombre'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label(__('Precio'))
                    ->sortable()
                    // ->money('eur')
                    ->formatStateUsing(fn ($record) => number_format($record->price, 2, ',', '.') . ' €'),
                TextColumn::make('category.name')
                    ->label(__('Categoría'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Creado'))
                    ->sortable()
                    ->date('d/m/Y H:i'),
                TextColumn::make('updated_at')
                    ->label(__('Actualizado'))
                    ->sortable()
                    ->date('d/m/Y H:i'),
                    
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label(__('Categoría'))
                    ->relationship('category', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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

    //Mosttar información en una ventana separada
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ImageEntry::make('image')
                    ->columnSpanFull()
                    ->hiddenLabel(),
                Section::make()->schema([
                    TextEntry::make('name')
                        ->label(__('Nombre')),
                    TextEntry::make('price')
                        ->label(__('Precio'))
                        ->money('eur'),
                    TextEntry::make('category.name')
                        ->label(__('Categoría')),
                ])->columns(3),
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
