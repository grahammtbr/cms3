<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;
use FilamentTiptapEditor\TiptapEditor;
use FilamentAddons\Forms\Components\TitleWithSlug;
use FilamentAddons\Admin\FixedSidebar;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $label = 'Category';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return FixedSidebar::make()
                ->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            TitleWithSlug::make('name', 'slug', '/')
                                ->columnSpan('full')
                        ])->columns(3),
                    // Forms\Components\Card::make()
                    //     ->schema([
                    //         MediaPicker::make('featured_image')
                    //             //->label('Image')
                    //     ]),
                    Tabs::make('Category')
                        ->tabs([
                            Tabs\Tab::make('Content')
                                ->schema([
                                    TiptapEditor::make('description')
                                        //->label(false)
                                        ->profile('simple'),
                                ]),
                            Tabs\Tab::make('SEO')
                                ->schema([
                                    Forms\Components\TextInput::make('meta_title'),
                                    Forms\Components\Textarea::make('excerpt')
                                        ->label('Excerpt/Meta Description')
                                        ->rows(4),
                                    Forms\Components\TextInput::make('canonical_url')
                                        ->label('Canonical URL')
                                        ->url()
                                        ->reactive(),
                                    Forms\Components\Group::make()
                                        ->schema([
                                            Forms\Components\Toggle::make('index')
                                                ->default(true)
                                                ->onIcon('heroicon-s-check')
                                                ->offIcon('heroicon-m-no-symbol')
                                                ->inline(false),
                                            Forms\Components\Toggle::make('follow')
                                                ->default(true)
                                                ->onIcon('heroicon-s-check')
                                                ->offIcon('heroicon-m-no-symbol')
                                                ->inline(false),
                                            Forms\Components\Toggle::make('archive')
                                                ->default(true)
                                                ->onIcon('heroicon-s-check')
                                                ->offIcon('heroicon-m-no-symbol')
                                                ->inline(false),
                                        ])->columns(3),
                                ])
                        ]),
                ], [
                    Forms\Components\Section::make('Category Status')
                        ->schema([
                            Forms\Components\Toggle::make('is_visible')
                                ->label('Visible to users')
                                ->default(true),
                            Forms\Components\Placeholder::make('created_at')
                                ->label('Created on')
                                ->content(fn (?Category $record): string => $record ? $record->created_at->toDayDateTimeString() : '-'),
                            Forms\Components\Placeholder::make('updated_at')
                                ->label('Last modified on')
                                ->content(fn (?Category $record): string => $record ? $record->updated_at->toDayDateTimeString() : '-'),
                        ])->collapsible(),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visible')
                    ->options([
                        'heroicon-o-x-circle' => false,
                        'heroicon-o-check-circle' => true,
                    ])
                    ->colors([
                        'primary',
                        'danger' => false,
                        'success' => true,
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y \a\t g:i a'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M d, Y \a\t g:i a'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
