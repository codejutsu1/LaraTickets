<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Dashboard\Pages\EditProfile;
use Illuminate\Session\Middleware\StartSession;
use App\Http\Middleware\VerifyIsAgentMiddleware;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class SupportPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('support')
            ->path('agent')
            ->profile()
            ->userMenuItems([
                'profile' => MenuItem::make()->url(fn (): string => route('filament.dashboard.pages.edit-profile')),

                MenuItem::make()
                        ->label('Admin Dashboard')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->url('/admin')
                        ->visible(fn (): bool => auth()->user()->isAdmin()),
                        
                MenuItem::make()
                        ->label('Dashboard')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->url('/dashboard')
            ])
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Support/Resources'), for: 'App\\Filament\\Support\\Resources')
            ->discoverPages(in: app_path('Filament/Support/Pages'), for: 'App\\Filament\\Support\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Support/Widgets'), for: 'App\\Filament\\Support\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                VerifyIsAgentMiddleware::class,
            ]);
    }
}
