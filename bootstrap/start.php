<?php

define('BFACP_VERSION', '2.0.0');

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
 */

$app = new Illuminate\Foundation\Application();

/*
|--------------------------------------------------------------------------
| Bind Paths
|--------------------------------------------------------------------------
|
| Here we are binding the paths configured in paths.php to the app. You
| should not be changing these here. If you need to change these you
| may do so within the paths.php file and they will be bound here.
|
 */

$app->bindInstallPaths(require __DIR__ . '/paths.php');

/*
|--------------------------------------------------------------------------
| Detect The Application Environment
|--------------------------------------------------------------------------
|
| Laravel takes a dead simple approach to your application environments
| so you can just specify a machine name for the host that matches a
| given environment, then we will automatically detect it for you.
|
 */

$env = $app->detectEnvironment(function () use ($app) {
    return require_once __DIR__ . '/environment.php';
});

/*
|--------------------------------------------------------------------------
| Load The Application
|--------------------------------------------------------------------------
|
| Here we will load this Illuminate application. We will keep this in a
| separate location so we can isolate the creation of an application
| from the actual running of the application with a given request.
|
 */

$framework = $app['path.base'] .
'/vendor/laravel/framework/src';

require $framework . '/Illuminate/Foundation/start.php';

if (!$app->runningInConsole()) {

    if (file_exists($app['path.base'] . '/app/bfacp/setup.php')) {
        if (!Schema::hasTable(Config::get('database.migrations'))) {
            require $app['path.base'] . '/app/bfacp/setup.php';
        }

        die(sprintf('Please delete installer located at %s', $app['path.base'] . '/app/bfacp/setup.php'));
    }

    $_SERVER['REMOTE_ADDR'] = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
}

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
 */

return $app;
