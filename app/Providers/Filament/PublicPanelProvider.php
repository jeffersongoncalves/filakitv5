<?php

namespace App\Providers\Filament;

use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin;

class PublicPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('public')
            ->path('')
            ->colors([
                'primary' => Color::Gray,
            ])
            ->brandLogo(fn () => Vite::asset(config('filakit.favicon.logo')))
            ->brandLogoHeight('50px')
            ->viteTheme('resources/css/filament/public/theme.css')
            ->defaultThemeMode(config('filakit.theme_mode', ThemeMode::Dark))
            ->discoverResources(in: app_path('Filament/Public/Resources'), for: 'App\\Filament\\Public\\Resources')
            ->discoverPages(in: app_path('Filament/Public/Pages'), for: 'App\\Filament\\Public\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Public/Widgets'), for: 'App\\Filament\\Public\\Widgets')
            ->widgets([])
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
            ])
            ->plugins([
                EnvironmentIndicatorPlugin::make()
                    ->showBadge()
                    ->showBorder()
                    ->showGitBranch()
                    ->visible(fn () => config('filakit.show_environment_indicator', false)),
            ])
            ->topNavigation()
            ->databaseNotifications(false);
    }
}
