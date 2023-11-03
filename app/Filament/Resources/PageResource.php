<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use FilamentTiptapEditor\TiptapEditor;
use FilamentTiptapEditor\Enums\TiptapOutput;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use App\Filament\Resources\Forms\Seo\SeoForm;
use Camya\Filament\Forms\Components\TitleWithSlugInput;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Page')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Page')
                            ->icon('heroicon-o-document') 
                            ->schema([
                                TitleWithSlugInput::make(
                                    fieldTitle: 'title',
                                    fieldSlug: 'slug',
                                )->columnSpan('full'),
                            Forms\Components\Builder::make('content')
                                ->label(false)
                                ->collapsible()
                                ->blocks([
                                    Forms\Components\Builder\Block::make('wysiwyg_editor')
                                        ->icon('heroicon-o-document-text')
                                        ->label('WYSIWYG Editor')
                                        ->schema([
                                            TiptapEditor::make('block_content')
                                                ->label(false)
                                                ->profile('default')
                                                ->maxContentWidth('5xl')
                                                ->extraInputAttributes(['style' => 'min-height: 14rem;']),
                                        ])
                                ])->createItemButtonLabel('+ Add Block')
                                ->columnSpan('full'),
                            ]),
                        Forms\Components\Tabs\Tab::make('Featured Image')
                            ->icon('heroicon-o-document') 
                            ->schema([
                                CuratorPicker::make('featured_image')
                                    ->label(false)
                                    ->columnSpan('full'),
                            ]),
                        Forms\Components\Tabs\Tab::make('Settings')
                            ->icon('heroicon-o-cog') 
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Fieldset::make('Status & Visibility')
                                            ->schema([
                                                Forms\Components\Toggle::make('is_live')
                                                    ->label('Live')
                                                    ->inline(false)
                                                    ->columnSpan(1)
                                                    ->disabled(fn (\Filament\Forms\Get $get) => $get('is_homepage') === true || $get('status') !== 'published'),
                                                Forms\Components\Select::make('status')
                                                    ->options([
                                                        'draft' => 'Draft',
                                                        'review' => 'In review',
                                                        'published' => 'Published',
                                                    ])->default('draft')
                                                    ->columnSpan(2)
                                                    ->disablePlaceholderSelection()
                                                    //->disabled(fn (\Filament\Forms\Get $get) => $get('is_homepage') === true)
                                                    ->reactive()
                                                    ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                                                        $state === 'published' ? $set('is_live', true) : $set('is_live', false);
                                                    }),
                                                Forms\Components\Toggle::make('is_homepage')
                                                    ->label('Homepage')
                                                    ->inline(false)
                                                    ->reactive()
                                                    ->columnSpan(1)
                                                    ->disabled(fn (\Filament\Forms\Get $get) => $get('status') !== 'published')
                                                    ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                                                        $state ? $set('is_live', true) : null;
                                                    }),
                                                Forms\Components\Select::make('visibility')
                                                    ->options([
                                                        'public' => 'Public',
                                                        'private' => 'Private',
                                                        'password' => 'Password Protected',
                                                    ])->default('public')
                                                    ->columnSpan(2)
                                                    ->disablePlaceholderSelection(),
                                            ])->columns(3)
                                            ->extraAttributes(['class' => 'h-full'])
                                            ->columnSpan(1),
                                        Forms\Components\Fieldset::make('Dates')
                                            ->schema([
                                                Forms\Components\DateTimePicker::make('published_at')
                                                    ->label('Published on')
                                                    ->columnSpan(2)
                                                    ->withoutSeconds()
                                                    ->native(false)
                                                    ->suffixIcon('heroicon-s-calendar-days'),
                                                Forms\Components\Placeholder::make('created_at')
                                                    ->label('Created on')
                                                    ->extraAttributes(['class' => 'text-sm'])
                                                    //->columnSpan(2)
                                                    ->content(fn (?Page $record): string => $record ? $record->created_at->format('M j, Y \a\t H:i') : '-'),
                                                Forms\Components\Placeholder::make('updated_at')
                                                    ->label('Last modified on')
                                                    ->extraAttributes(['class' => 'text-sm'])
                                                    //->columnSpan(2)
                                                    ->content(fn (?Page $record): string => $record ? $record->updated_at->format('M j, Y \a\t H:i') : '-'),
                                            ])->extraAttributes(['class' => 'h-full'])
                                            ->columnSpan(1),
                                        Forms\Components\Fieldset::make('Attributes')
                                            ->schema([
                                                Forms\Components\TextInput::make('order')
                                                    ->numeric()
                                                    ->columnSpan(1),
                                                // Forms\Components\Select::make('parent')
                                                //     ->label('Parent Page')
                                                //     ->options(fn (?Page $record) => $record->all()->except($record->id)->pluck('title', 'id'))
                                                //     ->searchable()
                                                //     ->columnSpan(2),
                                                Forms\Components\Select::make('template')
                                                    ->options([])
                                                    ->searchable()
                                                    ->columnSpan('full'),
                                            ])->columns(3)
                                            ->extraAttributes(['class' => 'h-full'])
                                            ->columnSpan(1),
                                    ]),
                            ])->columns(1),
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->icon('heroicon-m-magnifying-glass')
                            ->schema([
                                SeoForm::getSeoForm()
                            ])
                    ])->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(false),
                Tables\Actions\EditAction::make()
                    ->label(false),
                Tables\Actions\DeleteAction::make()
                    ->label(false)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }    
}
