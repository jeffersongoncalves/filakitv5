<?php

// config for JeffersonGoncalves/SecurityHeaders

return [

    /*
    |--------------------------------------------------------------------------
    | Static Response Headers
    |--------------------------------------------------------------------------
    |
    | Each entry is stamped onto every response handled by the middleware.
    | Set any value to `null` to skip that header entirely.
    |
    */

    'headers' => [
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'SAMEORIGIN',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(), payment=(), usb=(), browsing-topics=()',
        // Isolate the browsing context (XS-Leaks / Spectre defence-in-depth).
        // "-allow-popups" keeps analytics/GTM popups from being severed.
        'Cross-Origin-Opener-Policy' => 'same-origin-allow-popups',
        'X-Permitted-Cross-Domain-Policies' => 'none',
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy
    |--------------------------------------------------------------------------
    |
    | The CSP header is assembled from the associative `directives` map below,
    | preserving order. A directive whose value is `null` (or an empty string)
    | is emitted as a valueless directive (e.g. `upgrade-insecure-requests`).
    | A value may be a string or an array of source expressions. Set
    | `enabled` to `false` to drop the header altogether.
    |
    | Filament panels need 'unsafe-inline' and 'unsafe-eval' in script-src
    | (Alpine evaluates expressions with new Function and Filament renders
    | inline scripts), so this policy does NOT stop XSS: it limits where
    | scripts, styles, frames and form posts may come from. The CSP is off in
    | the `local` environment so the Vite dev server keeps working. Edit the
    | live values in the admin "Security headers" page (they override this
    | file once saved).
    |
    | Set `report-only` to true to emit `Content-Security-Policy-Report-Only`
    | instead of the enforcing header. `report-uri`/`report-to` are appended as
    | directives when non-null so violations can be collected.
    |
    */

    'csp' => [
        'enabled' => env('APP_ENV', 'production') !== 'local',

        // Emit Content-Security-Policy-Report-Only instead of the enforcing header.
        'report-only' => false,

        // Optional violation-reporting endpoints. Appended as CSP directives.
        // 'report-uri' is the legacy endpoint; 'report-to' references a
        // Reporting-API group name you configure via a Report-To/Reporting-Endpoints header.
        'report-uri' => null,
        'report-to' => null,

        'directives' => [
            'default-src' => "'self'",
            'script-src' => "'self' 'unsafe-inline' 'unsafe-eval'",
            'style-src' => "'self' 'unsafe-inline'",
            'img-src' => "'self' data: blob: https:",
            'font-src' => "'self' data:",
            'connect-src' => "'self'",
            'object-src' => "'none'",
            'base-uri' => "'self'",
            'form-action' => "'self'",
            'frame-ancestors' => "'self'",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Strict Transport Security (HSTS)
    |--------------------------------------------------------------------------
    |
    | HSTS is only stamped over real HTTPS and never while the application is
    | in the `local` environment (a cached max-age on a *.test domain is a
    | pain to undo). Toggle and tune the directive parameters below.
    |
    | `preload` defaults to FALSE: turning it on is a near-irreversible
    | commitment. Submitting your domain to hstspreload.org bakes HTTPS-only for
    | the apex AND every subdomain into browsers, and removal can take months to
    | propagate. Only enable it once you are certain every subdomain serves TLS.
    |
    */

    'hsts' => [
        'enabled' => true,
        'max-age' => 31536000,
        'include-subdomains' => true,
        'preload' => false,

        // Environments in which HSTS is never stamped (even over HTTPS). Defaults
        // to ['local'] so a cached max-age on a *.test domain never bites during
        // development. Set to [] to stamp HSTS in every environment.
        'exclude_environments' => ['local'],
    ],

];
