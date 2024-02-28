<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    //Ordenar los elementos de la navegación
    protected static ?int $navigationSort = 30;

    //Creación de grupos
    public static function getNavigationGroup(): string
    {
        return __('Almacén');
    }

    //Sobreesceibir la etiqueta
    public static function getLabel(): string
    {
        return __('Pedido');
    }

    //Sobreescribir el nombre de la opción de menú
    public static function getNavigationLabel(): string
    {
        return __('Pedidos');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(3)
                    ->schema([
                        Select::make('user_id')
                            ->label(__('Cliente'))
                            ->options(User::customers()->pluck('name', 'id'))
                            ->searchable(),
                        TextInput::make('total')
                            ->label(__('Total'))
                            ->suffix('€'),
                        Select::make('status')
                            ->label(__('Estado'))
                            ->options([
                                'pending' => __('Pendiente'),
                                'processing' => __('En proceso'),
                                'completed' => __('Completado'),
                                'declined' => __('Rechazado'),
                            ]),
                    ])
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->prefix('#')
                    ->suffix('#'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Cliente'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label(__('Total'))
                    ->searchable()
                    ->sortable()
                    ->suffix('€'),
                Tables\Columns\TextColumn::make('total_products')
                    ->label(__('Total productos'))
                    ->state(fn (Order $order) => $order->orderLines->sum('quantity')) // Use the imported Model class
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('Estado'))
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'completed' => 'success',
                        'declined' => 'danger',
                        default => 'gray',
                    })
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label(__('Cliente'))
                    ->options(User::customers()->pluck('name', 'id'))
                    ->searchable(),
                SelectFilter::make('status')
                    ->label(__('Estado'))
                    ->options([
                        'pending' => __('Pendiente'),
                        'processing' => __('En proceso'),
                        'completed' => __('Completado'),
                        'declined' => __('Rechazado'),
                    ]),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
