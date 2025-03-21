<link rel="apple-touch-icon" sizes="57x57" href="{{ Vite::asset('resources/favicon/apple-icon-57x57.png') }}">
<link rel="apple-touch-icon" sizes="60x60" href="{{ Vite::asset('resources/favicon/apple-icon-60x60.png') }}">
<link rel="apple-touch-icon" sizes="72x72" href="{{ Vite::asset('resources/favicon/apple-icon-72x72.png') }}">
<link rel="apple-touch-icon" sizes="76x76" href="{{ Vite::asset('resources/favicon/apple-icon-76x76.png') }}">
<link rel="apple-touch-icon" sizes="120x120" href="{{ Vite::asset('resources/favicon/apple-icon-120x120.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ Vite::asset('resources/favicon/apple-icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ Vite::asset('resources/favicon/apple-icon-180x180.png') }}">
<link rel="icon" type="image/png" sizes="192x192"
      href="{{ Vite::asset('resources/favicon/android-icon-192x192.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ Vite::asset('resources/favicon/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="96x96" href="{{ Vite::asset('resources/favicon/favicon-96x96.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ Vite::asset('resources/favicon/favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('/manifest.json', app()->isProduction()) }}">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="{{ Vite::asset('resources/favicon/ms-icon-144x144.png') }}">
<meta name="theme-color" content="#ffffff">
<meta name="mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-title" content="{{ config('filakit.favicon.manifest.name') }}"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
<meta content="{{ config('filakit.favicon.manifest.name') }}" property="og:site_name"/>
<meta property="og:url" content="{{ config('app.url') }}" data-rh="true"/>
<meta property="og:type" content="website" data-rh="true"/>
<meta property="og:title" content="{{ config('filakit.favicon.manifest.name') }}" data-rh="true"/>
<meta property="og:description" content="{{ config('filakit.favicon.manifest.name') }}" data-rh="true"/>
<meta property="og:image" content="{{ Vite::asset(config('filakit.favicon.logo')) }}" data-rh="true"/>
