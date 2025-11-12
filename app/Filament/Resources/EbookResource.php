<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EbookResource\Pages;
use App\Filament\Resources\EbookResource\RelationManagers;
use App\Models\Ebook;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EbookResource extends Resource
{
    protected static ?string $model = Ebook::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    
    protected static ?string $navigationLabel = 'Ebooks';
    
    protected static ?string $modelLabel = 'Ebook';
    
    protected static ?string $pluralModelLabel = 'Ebooks';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations générales')
                    ->schema([
                        Forms\Components\TextInput::make('titre')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        
                        Forms\Components\TextInput::make('auteur')
                            ->required()
                            ->maxLength(150),
                        
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'nom')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Prix et disponibilité')
                    ->schema([
                        Forms\Components\TextInput::make('prix')
                            ->required()
                            ->numeric()
                            ->prefix('FCFA')
                            ->minValue(0),
                        
                        Forms\Components\TextInput::make('prix_promo')
                            ->numeric()
                            ->prefix('FCFA')
                            ->minValue(0)
                            ->label('Prix promotionnel'),
                        
                        Forms\Components\Toggle::make('actif')
                            ->default(true)
                            ->inline(false),
                        
                        Forms\Components\Toggle::make('bestseller')
                            ->default(false)
                            ->inline(false),
                        
                        Forms\Components\Toggle::make('nouveau')
                            ->default(false)
                            ->inline(false),
                    ])->columns(3),
                
                Forms\Components\Section::make('Détails du livre')
                    ->schema([
                        Forms\Components\TextInput::make('pages')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                        
                        Forms\Components\TextInput::make('langue')
                            ->default('Français')
                            ->maxLength(50),
                        
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('ebooks/images')
                            ->imageEditor()
                            ->maxSize(2048),
                        
                        Forms\Components\FileUpload::make('fichier_pdf')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('ebooks/pdfs')
                            ->maxSize(51200)
                            ->label('Fichier PDF'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular()
                    ->defaultImageUrl('/images/placeholder.jpg'),
                
                Tables\Columns\TextColumn::make('titre')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('auteur')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('category.nom')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('prix')
                    ->money('XOF', locale: 'fr')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('prix_promo')
                    ->money('XOF', locale: 'fr')
                    ->sortable()
                    ->color('success'),
                
                Tables\Columns\IconColumn::make('bestseller')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('nouveau')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('actif')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('vues')
                    ->sortable()
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'nom'),
                
                Tables\Filters\TernaryFilter::make('actif'),
                Tables\Filters\TernaryFilter::make('bestseller'),
                Tables\Filters\TernaryFilter::make('nouveau'),
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
            'index' => Pages\ListEbooks::route('/'),
            'create' => Pages\CreateEbook::route('/create'),
            'edit' => Pages\EditEbook::route('/{record}/edit'),
        ];
    }
}
