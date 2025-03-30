<?php

namespace App\Http\Middleware;

use Closure;
use Lab404\Impersonate\Services\ImpersonateManager;

class ProtectFromImpersonation
{
    public function handle($request, Closure $next)
    {
        $impersonate_manager = app()->make(ImpersonateManager::class);

        if ($impersonate_manager->isImpersonating()) {
            return redirect()->route('filament.app.pages.dashboard');
        }

        return $next($request);
    }
}
