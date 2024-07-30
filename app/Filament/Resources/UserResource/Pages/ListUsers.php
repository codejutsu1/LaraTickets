<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Users')
                        ->icon('heroicon-m-user-group')
                        ->badge(User::count()),
            'admin' => Tab::make('Admin')
                            ->modifyQueryUsing(fn (Builder $query) => $query->where('role_id', 1))
                            ->badge(User::query()->where('role_id', 1)->count())
                            ->icon('heroicon-m-user'),
            'user' => Tab::make('Agents')
                            ->modifyQueryUsing(fn (Builder $query) => $query->where('role_id', 2))
                            ->badge(User::query()->where('role_id', 2)->count())
                            ->icon('heroicon-m-users'),
            'paidUser' => Tab::make('Users')
                            ->modifyQueryUsing(fn (Builder $query) => $query->where('role_id', 3))
                            ->badge(User::query()->where('role_id', 3)->count())
                            ->icon('heroicon-m-users'),
        ];
    }
}
