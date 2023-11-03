<?php

namespace App\Filament\Forms\Settings;

use Closure;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\PageResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\Action;
use Filament\Forms;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Tabs;
use BezhanSalleh\FilamentAddons\Forms\Components;
use FilamentCurator\Forms\Components\MediaPicker;

class SettingsForm
{
    public static function getSettingsForm($record)
    {
        //$data = $record;
        return [ Forms\Components\Fieldset::make('Status & Visibility')
            ->schema([
                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\Toggle::make('is_live')
                            ->label('Live')
                            ->inline()
                            ->disabled(fn (\Filament\Forms\Get $get) => $get('is_homepage') === true),
                        Forms\Components\Toggle::make('is_homepage')
                            ->label('Homepage')
                            ->inline()
                            ->reactive()
                            ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                                $state ? $set('is_live', true) : null;
                            }),
                    ]),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'review' => 'In review',
                                'published' => 'Published',
                            ])->default('draft')
                            //->columnSpan(1)
                            ->disablePlaceholderSelection(),
                        Forms\Components\Select::make('visibility')
                            ->options([
                                'public' => 'Public',
                                'private' => 'Private',
                                'password' => 'Password Protected',
                            ])->default('public')
                            //->columnSpan(1)
                            ->disablePlaceholderSelection(),
                    ]),
            ])->columns(1)
            ->columnSpan(1),
            Forms\Components\Fieldset::make('Dates')
                ->schema([
                    Forms\Components\DateTimePicker::make('published_at')
                        ->label('Published on')
                        ->columnSpan(2)
                        ->withoutSeconds(),
                    Forms\Components\Placeholder::make('created_at')
                        //->label('Created on')
                        //->columnSpan(2)
                        ->content(fn (): string => $record ? $record->updated_at->format('M j, Y \a\t g:i a') : '-'),
                    Forms\Components\Placeholder::make('updated_at')
                        ->label('Last modified on')
                        //->columnSpan(2)
                        ->content(fn (): string => $record ? $record->updated_at->format('M j, Y \a\t g:i a') : '-'),
                        //])
                ])->columns(2)
                ->columnSpan(1),
            Forms\Components\Fieldset::make('Attributes')
                ->schema([
                    Forms\Components\TextInput::make('order')
                        ->numeric()
                        ->columnSpan(1),
                    Forms\Components\Select::make('parent')
                        ->label('Parent Page')
                        ->options($record::all()->except($record->id)->pluck('title', 'id'))
                        ->searchable()
                        ->columnSpan(2),
                ])->columns(3),
        ];
    }
}