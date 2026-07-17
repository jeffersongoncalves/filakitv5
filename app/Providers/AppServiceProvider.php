<?php

namespace App\Providers;

use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\AppPanelProvider;
use App\Providers\Filament\GuestPanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use STS\FilamentImpersonate\Actions\Impersonate;

use function view;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (config('filakit.admin_panel_enabled', false)) {
            $this->app->register(AdminPanelProvider::class);
        }
        if (config('filakit.app_panel_enabled', false)) {
            $this->app->register(AppPanelProvider::class);
        }
        if (config('filakit.guest_panel_enabled', false)) {
            $this->app->register(GuestPanelProvider::class);
        }
        FilamentView::registerRenderHook(PanelsRenderHook::HEAD_START, fn (): View => view('components.js-md5'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! app()->isLocal()) {
            URL::forceHttps();
            Vite::useAggressivePrefetching();
        }

        Model::automaticallyEagerLoadRelationships();

        $this->configureImpersonate();
    }

    private function configureImpersonate(): void
    {
        Impersonate::configureUsing(function (Impersonate $action) {
            return $action
                ->color(Color::Blue)
                ->hiddenLabel()
                ->button();
        });
    }
}
