<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Forms;
use RyanChandler\FilamentNavigation\Facades\FilamentNavigation;
use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource;
use App\Models\Page;
use App\Models\Post;
use Filament\Navigation\NavigationGroup;
use FilamentStickyHeader\Facades\StickyHeader;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Filament::serving(function () {
        //     Filament::registerTheme(mix('css/app.css'));
        // });

        // Filament::registerNavigationGroups([
        //     'Blog',
        //     'Content',
        //     'Forms',
        //     'Admin',
        // ]);

        NavigationResource::label('Menu');
        // FilamentNavigation::addItemType('Page link', [
        //     Forms\Components\Select::make('page_id')
        //         ->label('Page')
        //         ->placeholder('Select a page')
        //         ->searchable()
        //         ->options(function () {
        //             return Page::pluck('title', 'id');
        //         })
        // ]);
        // FilamentNavigation::addItemType('Post link', [
        //     Forms\Components\Select::make('post_id')
        //         ->label('Post')
        //         ->placeholder('Select a post')
        //         ->searchable()
        //         ->options(function () {
        //             return Post::pluck('title', 'id');
        //         })
        // ]);
    }
}
