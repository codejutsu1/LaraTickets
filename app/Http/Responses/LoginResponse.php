<?php

namespace App\Http\Responses;
 
use App\Enum\UserRole;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use App\Filament\Resources\OrderResource;
use Livewire\Features\SupportRedirects\Redirector;
 
class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $user = Filament::auth()->user();
        $url = '/';

        if ($user) {
            $role = UserRole::fromId($user->role_id);
            
            $url = match ($role) {
                UserRole::USER => '/dashboard',
                UserRole::AGENT => '/agent',
                UserRole::ADMIN => '/admin',
                default => '/',
            };
        }

        return redirect()->to($url);
    }
}