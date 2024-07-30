<?php

namespace App\Filament\Support\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Ticket;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Dashboard\Resources\CommentResource;
use App\Filament\Support\Resources\TicketResource\Pages;
use App\Filament\Support\Resources\TicketResource\RelationManagers;
use App\Filament\Dashboard\Resources\TicketResource as UserTicketResource;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-signal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(UserTicketResource::ticketTable())
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('attendTicket')
                            ->label('Attend')
                            ->visible(fn(Ticket $ticket): bool => $ticket->is_open)
                            ->icon('heroicon-o-chat-bubble-left-ellipsis')
                            ->url(function(Ticket $record){
                                $link = route('filament.dashboard.resources.comments.comment', ['record' => $record->tracking_id]);
                                
                                return $link;
                            })
                            ->openUrlInNewTab(),

                    Action::make('markedAsAttended')
                            ->label('Marked As Attended')
                            ->visible(fn(Ticket $ticket): bool => $ticket->is_open)
                            ->icon('heroicon-o-check-circle')
                            ->action(function(Ticket $record) {
                                $record->update(['is_open' => 0]);

                                Notification::make()
                                            ->title('Ticket Marked as Attended')
                                            ->success()
                                            ->send();
                            }),
                ]),
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            // 'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
