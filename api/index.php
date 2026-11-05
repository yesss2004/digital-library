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

/*
|--------------------------------------------------------------------------
| Storage path
|--------------------------------------------------------------------------
|
| Vercel's filesystem is read-only except for /tmp. Move Laravel's storage
| directory (views, sessions, cache, logs, uploads) to /tmp so the app can
| boot and render Blade views on the serverless runtime.
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
|--------------------------------------------------------------------------
| Writable caches
|--------------------------------------------------------------------------
|
| composer install runs with --no-scripts on Vercel, so Laravel cannot write
| its package/service manifests into bootstrap/cache. Point those caches to
| /tmp as well.
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
|--------------------------------------------------------------------------
| Application URL
|--------------------------------------------------------------------------
|
| Vercel exposes the deployment host through VERCEL_URL. Use it when APP_URL
| has not been configured explicitly so asset() and @vite keep working.
|
*/

if (! getenv('APP_URL')) {
    $host = $_SERVER['VERCEL_PROJECT_PRODUCTION_URL']
        ?? $_SERVER['VERCEL_URL']
        ?? null;

    if ($host) {
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

$app->useStoragePath($storagePath);

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
