<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use BezhanSalleh\FilamentAddons\Forms\Components;
use FilamentCurator\Forms\Components\MediaPicker;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save_changes')->action('save'),
            DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
