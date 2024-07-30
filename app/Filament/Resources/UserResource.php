<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Users Information')
                        ->schema([
                            TextInput::make('name')
                                    ->required(),

                            TextInput::make('email')
                                    ->email()
                                    ->required(),

                            TextInput::make('password')
                                    ->password()
                                    ->required(),

                            TextInput::make('password_confirmation')
                                    ->password()
                                    ->required(),
                        ])->columns(2),

                Section::make('User Role')
                        ->schema([
                            Select::make('role_id')
                                    ->relationship(name:'role', titleAttribute:'name')
                                    ->placeholder('Select Role')
                                    ->native(false),
                        ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                            ->searchable()
                            ->sortable(),

                TextColumn::make('email')
                            ->searchable()
                            ->sortable(),
                
                TextColumn::make('role.name')
                            ->label('Role')
                            ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
