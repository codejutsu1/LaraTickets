<?php

namespace App\Filament\Dashboard\Resources\TicketResource\Pages;

use App\Filament\Dashboard\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
    
        return $data;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Ticket successfully created.';
    }
}
