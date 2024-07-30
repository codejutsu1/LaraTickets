<?php

namespace App\Filament\Dashboard\Resources\CommentResource\Pages;

use Filament\Actions;
use App\Models\Comment;
use Filament\Actions\Action;
use Filament\Infolists\Infolist;
use Illuminate\Contracts\View\View;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Dashboard\Resources\CommentResource;
use Filament\Infolists\Components\TextEntry\TextEntrySize;

class ViewComment extends ViewRecord
{
    protected static string $resource = CommentResource::class;

    protected static string $view = 'filament.pages.comment';

    public function getHeader(): ?View
    {
        return view('filament.custom.header');
    }

    public function editAction(): Action
    {
        return Action::make('edit')
            ->label('Make A Comment')
            ->icon('heroicon-m-pencil-square')
            ->labeledFrom('md')
            ->size(ActionSize::Large)
            ->outlined()
            ->modalSubmitActionLabel('Comment')
            ->form([
                MarkdownEditor::make('message')
                                ->label("Comment")
                                ->required(),
            ])
            ->action(function (array $data) {
               $data['user_id'] = auth()->id();
               $data['ticket_id'] = $this->record->id;

               Comment::create($data);

               Notification::make()
                            ->title('Comment successful')
                            ->success()
                            ->send();
            });
    } 
    
    public function ticketInfolist(Infolist $infolist): Infolist
    {
        return $infolist
                ->record($this->record)
                ->schema([
                    Section::make('Ticket')
                    ->id('ticket')
                    ->schema([
                            Section::make()
                                ->id('ticketUser')
                                ->schema([
                                    TextEntry::make('user.name')
                                            ->label('')
                                            ->size(TextEntrySize::Medium)
                                            ->weight(FontWeight::Bold)
                                            ->url(function () {
                                                return '';
                                                // return SocialResource::getUrl('profile', ['record' => $identifier]);
                                            }),
                                    TextEntry::make('created_at')
                                            ->label('')
                                            ->size(TextEntrySize::Small)
                                            ->since(),
                                    TextEntry::make('subject')
                                                ->size(TextEntrySize::Medium),
                                    TextEntry::make('priority')
                                                ->size(TextEntrySize::Medium),
                                    TextEntry::make('message')
                                            ->label('Message')
                                            ->markdown()
                                            ->size(TextEntrySize::Medium)
                                            ->columnSpan('full'),
                            ])
                            ->columns(2)
                    ]),
                ]);
    }
}
