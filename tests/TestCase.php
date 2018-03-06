<?php

namespace AlfredNutileInc\LarScanner;

use AlfredNutileInc\LarScanner\Providers\LarScannerProvider;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Mockery;
use Dotenv\Dotenv;

class TestCase extends \Orchestra\Testbench\TestCase
{

    protected function getPackageProviders($app)
    {
        return
            [
            LarScannerProvider::class,
        ];
    }


    protected function getEnvironmentSetUp($app)
    {

        $path = __DIR__ . "/../";
        $dotenv = new Dotenv($path);
        $dotenv->load();

        $app->configureMonologUsing(function ($monolog) {
            $path = __DIR__ . "/logs/laravel.log";

            $handler = $handler = new StreamHandler($path, 'debug');

            $handler->setFormatter(tap(new LineFormatter(null, null, true, true), function ($formatter) {
                /** @var LineFormatter $formatter */
                $formatter->includeStacktraces();
            }));

            /** @var \Monolog\Logger $monolog */
            $monolog->pushHandler($handler);
        });

        $app['config']->set('app.debug', env('APP_DEBUG', true));

        $app['config']->set('services.security_scanner.user', env('SECURITY_SCANNER_USER_NAME', null));
        $app['config']->set('services.security_scanner.key', env('SECURITY_SCANNER_USER_JOB_KEY', null));


        $destination = __DIR__ . '/storage';

        $app['config']->set('cache.stores.file.path', $destination);
    }

}
