<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\Tabs;
use Closure;
use Illuminate\Support\Str;
use FilamentTiptapEditor;
use FilamentTiptapEditor\TiptapEditor;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use FilamentAddons\Forms\Components\TitleWithSlug;
use Awcodes\FilamentBadgeableColumn\Components\Badge;
use Awcodes\FilamentBadgeableColumn\Components\BadgeField;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use Camya\Filament\Forms\Components\TitleWithSlugInput;
use App\Filament\Forms\Seo\SeoForm;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    //protected static ?string $label = 'Blog Post';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Page')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Post')
                            ->icon('heroicon-o-document') 
                            ->schema([
                                // TitleWithSlug::make('title', 'slug', '/')
                                //     ->columnSpan('full'),
                                TitleWithSlugInput::make(
                                    fieldTitle: 'title',
                                    fieldSlug: 'slug',
                                )->columnSpan('full'),
                                Forms\Components\Section::make('Featured Image')
                                    ->schema([
                                        CuratorPicker::make('featured_image')
                                            ->label(false)
                                            ->columnSpan('full'),
                                    ])->collapsible()
                                    ->compact(),
                                // Forms\Components\Builder::make('content')
                                //     ->label(false)
                                //     ->collapsible()
                                //     ->blocks([
                                //         Forms\Components\Builder\Block::make('text_content')
                                //             ->schema([
                                                
                                    //         ])
                                    // ])->createItemButtonLabel('+ Add Block')
                                    // ->columnSpan('full'),
                            ]),
                        Forms\Components\Tabs\Tab::make('Categories & Tags')
                            ->icon('heroicon-o-tag') 
                            ->schema([
                                Forms\Components\CheckboxList::make('authorId')
                                    ->relationship('categories', 'name')
                                    //->columns(2)
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
                                                    ->disabled(fn (\Filament\Forms\Get $get) => $get('status') !== 'published'),
                                                Forms\Components\Select::make('status')
                                                    ->options([
                                                        'draft' => 'Draft',
                                                        'review' => 'In review',
                                                        'published' => 'Published',
                                                    ])->default('draft')
                                                    ->columnSpan(2)
                                                    ->disablePlaceholderSelection()
                                                    ->disabled(fn (\Filament\Forms\Get $get) => $get('is_homepage') === true)
                                                    ->reactive()
                                                    ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                                                        $state === 'published' ? $set('is_live', true) : $set('is_live', false) && $set('is_pinned', false);
                                                    }),
                                                Forms\Components\Toggle::make('is_pinned')
                                                    ->label('Pin to top')
                                                    ->inline(false)
                                                    ->reactive()
                                                    ->columnSpan(1)
                                                    ->disabled(fn (\Filament\Forms\Get $get) => $get('status') !== 'published'),
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
                                                    ->withoutSeconds(),
                                                Forms\Components\Placeholder::make('created_at')
                                                    ->label('Created on')
                                                    ->extraAttributes(['class' => 'text-sm'])
                                                    //->columnSpan(2)
                                                    ->content(fn (?Post $record): string => $record ? $record->created_at->format('M j, Y \a\t H:i') : '-'),
                                                Forms\Components\Placeholder::make('updated_at')
                                                    ->label('Last modified on')
                                                    ->extraAttributes(['class' => 'text-sm'])
                                                    //->columnSpan(2)
                                                    ->content(fn (?Post $record): string => $record ? $record->updated_at->format('M j, Y \a\t H:i') : '-'),
                                            ])->extraAttributes(['class' => 'h-full'])
                                            ->columnSpan(1),
                                        Forms\Components\Fieldset::make('Attributes')
                                            ->schema([
                                                // Forms\Components\TextInput::make('order')
                                                //     ->numeric()
                                                //     ->columnSpan(1),
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
                    
                    Forms\Components\Section::make('Content')
                        ->schema([
                            TiptapEditor::make('content')
                                ->label(false)
                                ->extraInputAttributes(['style' => 'min-height: 16rem;'])
                                ->profile('default'),
                        ])->columnSpan(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // BadgeableColumn::make('title')
                //     ->badges([
                //         Badge::make('is_pinned')
                //             ->label('Pinned')
                //             ->color('primary')
                //             ->visible(fn ($record): bool => $record->is_pinned),
                //         BadgeField::make('status')
                //             ->options([
                //                 'draft' => 'Draft',
                //                 'review' => 'In Review',
                //                 'published' => 'Published'
                //             ])
                //             ->colors([
                //                 'danger' => 'draft',
                //                 'warning' => 'review',
                //                 'success' => 'published',
                //             ]),
                //     ]),
                // Tables\Columns\BadgeColumn::make('status')
                //     ->enum([
                //         'draft' => 'Draft',
                //         'review' => 'In Review',
                //         'published' => 'Published',
                //     ])
                //     ->colors([
                //         'primary',
                //         'danger' => 'draft',
                //         'warning' => 'review',
                //         'success' => 'published',
                //     ]),
                // Tables\Columns\TextColumn::make('published_at')
                //     ->label('Date')
                //     ->dateTime('M d, Y \a\t g:i a')
                //     ->getStateUsing(function (Post $record): string {
                //         return $record->published_at ? $record->published_at : $record->updated_at;
                //     })
                //     ->description(fn (Post $record): string => $record->published_at ? 'Published on' : 'Last Updated', position: 'above'),
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
                    ->label(false),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
