<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationLabel = 'Commandes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('numero_commande')
                    ->disabled(),
                
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('prenom')
                            ->required(),
                        Forms\Components\TextInput::make('nom')
                            ->required(),
                    ]),
                
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                
                Forms\Components\Select::make('statut')
                    ->options([
                        'en_attente' => 'En attente',
                        'payee' => 'Payée',
                        'annulee' => 'Annulée',
                    ])
                    ->required(),
                
                Forms\Components\TextInput::make('montant_total')
                    ->numeric()
                    ->prefix('FCFA')
                    ->disabled(),
                
                Forms\Components\TextInput::make('methode_paiement')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero_commande')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('prenom')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('nom')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('montant_total')
                    ->money('XOF', locale: 'fr')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('statut')
                    ->colors([
                        'warning' => 'en_attente',
                        'success' => 'payee',
                        'danger' => 'annulee',
                    ])
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('methode_paiement')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('statut')
                    ->options([
                        'en_attente' => 'En attente',
                        'payee' => 'Payée',
                        'annulee' => 'Annulée',
                    ]),
                
                Tables\Filters\SelectFilter::make('methode_paiement')
                    ->options([
                        'carte' => 'Carte bancaire',
                        'paypal' => 'PayPal',
                        'mobile_money' => 'Mobile Money',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
