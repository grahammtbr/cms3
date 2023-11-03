<?php

namespace App\Filament\Resources;

use Closure;
use App\Filament\Resources\FormResource\Pages;
use App\Filament\Resources\FormResource\RelationManagers;
use App\Models\Form as UserForm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class FormResource extends Resource
{
    protected static ?string $model = UserForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationGroup = 'Forms';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(FormResource::getFormSchema());
    }

    public static function getFormSchema(): array
    {
        return [
            Forms\Components\Builder::make('fields')
                ->blocks([
                    Block::make('text')
                        ->label('Text input')
                        ->icon('heroicon-o-chat-bubble-bottom-center-text')
                        ->schema([
                            static::getFieldNameInput(),
                            Checkbox::make('is_required'),
                        ]),
                    Block::make('select')
                        ->icon('heroicon-o-chevron-up-down')
                        ->schema([
                            static::getFieldNameInput(),
                            KeyValue::make('options')
                                ->addButtonLabel('Add option')
                                ->keyLabel('Value')
                                ->valueLabel('Label'),
                            Checkbox::make('is_required'),
                        ]),
                    Block::make('checkbox')
                        ->icon('heroicon-o-check-circle')
                        ->schema([
                            static::getFieldNameInput(),
                            Checkbox::make('is_required'),
                        ]),
                    Block::make('file')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            static::getFieldNameInput(),
                            Grid::make()
                                ->schema([
                                    Checkbox::make('is_multiple'),
                                    Checkbox::make('is_required'),
                                ]),
                        ]),
                ])->columnSpan(2)
                ->createItemButtonLabel('Add form field')
                ->disableLabel(),
        ];
    }

    public static function getFieldNameInput(): Grid
    {
        return Grid::make()
            ->schema([
                TextInput::make('name')
                    ->lazy()
                    ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                        $label = Str::of($state)
                            ->kebab()
                            ->replace(['-', '_'], ' ')
                            ->ucfirst();

                        $set('label', $label);
                    })
                    ->required(),
                TextInput::make('label')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListForms::route('/'),
            'create' => Pages\CreateForm::route('/create'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
        ];
    }    
}
