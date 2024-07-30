<?php

namespace App\Filament\Dashboard\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enum\Priority;
use App\Models\Ticket;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Dashboard\Resources\TicketResource\Pages;
use App\Filament\Dashboard\Resources\TicketResource\RelationManagers;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-signal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Open Ticket')
                        ->schema([
                            TextInput::make('tracking_id')
                                    ->label('Tracking ID')
                                    ->default('TR-' . random_int(100000, 999999))
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(32)
                                    ->unique(Ticket::class, 'tracking_id', ignoreRecord: true),

                            Select::make('priority')
                                    ->required()
                                    ->options(Priority::class)
                                    ->native(false),

                            TextInput::make('subject')
                                    ->maxLength(100)
                                    ->required()
                                    ->columnSpan('full'),

                            MarkdownEditor::make('message')
                                    ->maxLength(100)
                                    ->required()
                                    ->columnSpan('full'),
                        ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::ticketTable())
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    public static function ticketTable(): array
    {
        return [
            TextColumn::make('tracking_id')
                            ->label('TrackingID')
                            ->searchable()
                            ->sortable()
                            ->badge(),

                TextColumn::make('subject')
                            ->searchable(),

                TextColumn::make('priority')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'low' => 'warning',
                                'medium' => 'info',
                                'high' => 'danger',
                            }),

                IconColumn::make('is_open')
                            ->label('Opened')
                            ->boolean(),

                // TextColumn::make('agent.name')
                //             ->label('Assigned Agent')
                //             ->searchable()
                //             ->default('None'),

                TextColumn::make('created_at')
                            ->dateTime()
                            ->toggleable(),
        ];
    }
}
