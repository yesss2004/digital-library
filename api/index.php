<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Vercel environment
|--------------------------------------------------------------------------
|
| The vercel-php runtime starts PHP as a CLI server, so make sure every
| platform variable is visible through $_ENV/$_SERVER as well as getenv().
| Laravel's env() helper reads from $_ENV and $_SERVER.
|
*/

foreach (getenv() as $key => $value) {
    if (! isset($_ENV[$key])) {
        $_ENV[$key] = $value;
    }

    if (! isset($_SERVER[$key])) {
        $_SERVER[$key] = $value;
    }
}

$isVercel = isset($_ENV['VERCEL'])
    || isset($_ENV['VERCEL_URL'])
    || isset($_ENV['VERCEL_ENV'])
    || isset($_ENV['NOW_REGION']);

/*
|--------------------------------------------------------------------------
| App key fallback
|--------------------------------------------------------------------------
|
| vercel.json already injects APP_KEY, but keeping a fallback here means the
| app still boots if the dashboard environment was overwritten or cleared.
|
*/

if (! isset($_ENV['APP_KEY']) || $_ENV['APP_KEY'] === '') {
    $_ENV['APP_KEY'] = 'base64:MkHtkd4tW+VWGF/Ot7PZSjp3XsS2jwpsDl9QHbplFM4=';
    $_SERVER['APP_KEY'] = $_ENV['APP_KEY'];
}

// Only a Linux filesystem exposes a writable /tmp, so local Windows/macOS
// development keeps using the project's own storage directory.
$useTempStorage = $isVercel
    || (DIRECTORY_SEPARATOR === '/' && is_dir('/tmp') && is_writable('/tmp'));

$storagePath = null;

if ($useTempStorage) {
    /*
    |----------------------------------------------------------------------
    | Storage path
    |----------------------------------------------------------------------
    |
    | Vercel's filesystem is read-only except for /tmp. Move Laravel's
    | storage directory (views, sessions, cache, logs, uploads) to /tmp so
    | the app can boot and render Blade views on the serverless runtime.
    |
    */

    $storagePath = rtrim(getenv('LARAVEL_STORAGE_PATH') ?: '/tmp/storage', '/');

    $storageDirectories = [
        '',
        'app',
        'app/public',
        'bootstrap-cache',
        'framework',
        'framework/cache',
        'framework/cache/data',
        'framework/sessions',
        'framework/views',
        'logs',
    ];

    foreach ($storageDirectories as $directory) {
        $path = $directory === '' ? $storagePath : $storagePath.'/'.$directory;

        if (! is_dir($path)) {
            @mkdir($path, 0777, true);
        }
    }

    /*
    |----------------------------------------------------------------------
    | Writable caches
    |----------------------------------------------------------------------
    |
    | composer install runs with --no-scripts on Vercel, so Laravel cannot
    | write its package/service manifests into bootstrap/cache. Point those
    | caches to /tmp as well.
    |
    */

    $temporaryPaths = [
        'VIEW_COMPILED_PATH' => $storagePath.'/framework/views',
        'APP_CONFIG_CACHE' => $storagePath.'/bootstrap-cache/config.php',
        'APP_EVENTS_CACHE' => $storagePath.'/bootstrap-cache/events.php',
        'APP_PACKAGES_CACHE' => $storagePath.'/bootstrap-cache/packages.php',
        'APP_ROUTES_CACHE' => $storagePath.'/bootstrap-cache/routes-v7.php',
        'APP_SERVICES_CACHE' => $storagePath.'/bootstrap-cache/services.php',
    ];

    foreach ($temporaryPaths as $key => $value) {
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }

    /*
    |----------------------------------------------------------------------
    | HTTPS
    |----------------------------------------------------------------------
    |
    | Vercel terminates TLS at the edge and forwards plain HTTP internally.
    | Mark the request as secure so Laravel builds https:// URLs and sets
    | secure cookies instead of redirecting back to http://.
    |
    */

    $forwardedProto = $_SERVER['HTTP_X_FORWARDED_PROTO']
        ?? $_ENV['HTTP_X_FORWARDED_PROTO']
        ?? 'https';

    if (str_contains(strtolower((string) $forwardedProto), 'https')) {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_PORT'] = '443';
    }
}

/*
|--------------------------------------------------------------------------
| Application URL
|--------------------------------------------------------------------------
|
| Use the domain the visitor actually opened (forwarded host), then fall
| back to Vercel's stable production/preview URLs. An explicit APP_URL
| from the dashboard always wins.
|
*/

if (! isset($_ENV['APP_URL']) || $_ENV['APP_URL'] === '') {
    $host = $_SERVER['HTTP_X_FORWARDED_HOST']
        ?? $_SERVER['HTTP_HOST']
        ?? $_SERVER['VERCEL_PROJECT_PRODUCTION_URL']
        ?? $_SERVER['VERCEL_URL']
        ?? null;

    if ($host) {
        $host = explode(',', (string) $host)[0];
        $appUrl = (str_starts_with($host, 'http') ? $host : 'https://'.$host);
        $_ENV['APP_URL'] = $appUrl;
        $_SERVER['APP_URL'] = $appUrl;
    }
}

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
*/

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

if ($storagePath) {
    $app->useStoragePath($storagePath);
}

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
