<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Vite;

abstract class FaviconSupport
{
    public static function routes(): void
    {
        if (config('filakit.favicon.enable', false)) {
            Route::any('manifest.json', fn () => self::getManifestJson());
            Route::any('browserconfig.xml', fn () => self::getBrowserConfigXml());
            if (! empty(config('filakit.favicon.logo'))) {
                Route::any('logo.png', fn () => self::getLogo());
            }
            if (! empty(config('filakit.favicon.favicon'))) {
                Route::any('favicon.ico', fn () => self::getFavicon());
            }
        }
    }

    private static function getManifestJson(): JsonResponse
    {
        $manifest = config('filakit.favicon.manifest');
        $manifest['icons'] = self::parseIcons($manifest['icons'] ?? []);

        return response()->json($manifest);
    }

    private static function parseIcons(array $icons): array
    {
        $data = [];
        foreach ($icons as $sizes => $density) {
            $data[] = self::getIconAndroid((string) $sizes, (string) $density);
        }

        return $data;
    }

    private static function getIconAndroid(string $size, string $density): array
    {
        return [
            'src' => Vite::asset('resources/favicon/android-icon-'.$size.'x'.$size.'.png'),
            'sizes' => $size.'x'.$size,
            'type' => 'image/png',
            'density' => $density,
        ];
    }

    private static function getBrowserConfigXml(): Response
    {
        $square70x70logo = Vite::asset('resources/favicon/ms-icon-70x70.png');
        $square150x150logo = Vite::asset('resources/favicon/ms-icon-150x150.png');
        $square310x310logo = Vite::asset('resources/favicon/ms-icon-310x310.png');
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<browserconfig>
    <msapplication>
        <tile>
            <square70x70logo src=\"{$square70x70logo}\"/>
            <square150x150logo src=\"{$square150x150logo}\"/>
            <square310x310logo src=\"{$square310x310logo}\"/>
            <TileColor>#ffffff</TileColor>
        </tile>
    </msapplication>
</browserconfig>";

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    private static function getLogo(): Response
    {
        return response(Vite::content(config('filakit.logo')), 200, ['Content-Type' => 'image/png']);
    }

    private static function getFavicon(): Response
    {
        return response(Vite::content(config('filakit.favicon')), 200, ['Content-Type' => 'image/x-icon']);
    }
}
