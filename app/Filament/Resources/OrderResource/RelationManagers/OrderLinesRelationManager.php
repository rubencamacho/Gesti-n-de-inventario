<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
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
                Hidden::make('order_id')
                    ->default($this->ownerRecord->id),
                Grid::make()
                    ->columns(3)
                    ->schema([
                        Select::make('product_id')
                            ->label(__('Producto'))
                            ->placeholder(__('Selecciona un producto'))
                            ->options(
                                Product::query()
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (Select $component, Set $set) {
                                $product = Product::query()
                                    ->where('id', $component->getState())
                                    ->first();
                                
                                $set('unit_price', $product?->price ?? 0);
                            }),
                        TextInput::make('quantity')
                            ->numeric()
                            ->label(__('Cantidad'))
                            ->placeholder(__('Introduce la cantidad'))
                            ->type('number')
                            ->default(1)
                            ->required(),
                        TextInput::make('unit_price')
                            ->label(__('Precio unitario'))
                            ->placeholder(__('Introduce el precio unitario'))
                            ->default(0)
                            ->suffix('€')
                            ->required(),
                        ]),
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
