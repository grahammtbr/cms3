<?php

namespace App\Filament\Resources\Forms\Seo;

use Closure;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\PageResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\Action;
use Filament\Forms;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Tabs;
//use BezhanSalleh\FilamentAddons\Forms\Components;
use Awcodes\Curator\Components\Forms\CuratorPicker;

class SeoForm
{
    public static function getSeoForm()
    {
        return Forms\Components\Group::make()
            ->relationship('seo')
            ->schema([
                Forms\Components\Tabs::make('SEO')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Meta Tags')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label('Meta Title'),
                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(4),
                            ]),
                        Forms\Components\Tabs\Tab::make('Social Tags')
                            ->schema([
                                CuratorPicker::make('meta_image')
                                    ->label('Social Image'),
                                Forms\Components\Section::make('Open Graph')
                                    ->schema([
                                        Forms\Components\TextInput::make('og_title')
                                            ->label('Open Graph Title'),
                                        Forms\Components\Textarea::make('og_description')
                                            ->label('Open Graph Description')
                                            ->rows(3),
                                    ])->compact()
                                    ->collapsible(),
                                Forms\Components\Section::make('Twitter Card')
                                    ->schema([
                                        Forms\Components\TextInput::make('tw_title')
                                            ->label('Twitter Title'),
                                        Forms\Components\Textarea::make('tw_description')
                                            ->label('Twitter Description')
                                            ->rows(3),
                                    ])->compact()
                                    ->collapsible(),
                                
                            ]),
                        Forms\Components\Tabs\Tab::make('Indexing')
                            ->schema([
                                Forms\Components\Grid::make(1)
                                    ->schema([
                                        Forms\Components\TextInput::make('canonical_url')
                                            ->label('Canonical URL')
                                            ->url(),
                                        Forms\Components\TextInput::make('redirect_url')
                                            ->label('Redirect URL')
                                            ->url(),
                                    ])->columnSpan(2),
                                Forms\Components\Fieldset::make('Robots Meta Settings')
                                    ->schema([
                                        Forms\Components\Toggle::make('noindex')
                                            //->default(true)
                                            ->onIcon('heroicon-s-check')
                                            ->offIcon('heroicon-m-no-symbol')
                                            ->inline(),
                                        Forms\Components\Toggle::make('nofollow')
                                            //->default(true)
                                            ->onIcon('heroicon-s-check')
                                            ->offIcon('heroicon-m-no-symbol')
                                            ->inline(),
                                        Forms\Components\Toggle::make('noarchive')
                                            //->default(true)
                                            ->onIcon('heroicon-s-check')
                                            ->offIcon('heroicon-m-no-symbol')
                                            ->inline(),
                                    ])->columnSpan(1)
                                    ->columns(1),
                            ])->columns(3),
                    ])
            ]);
    }
}