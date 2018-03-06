<?php

namespace AlfredNutileInc\LarScanner\Providers;

use AlfredNutileInc\LarScanner\Console\SensioScanCommand;
use AlfredNutileInc\LarScanner\Listeners\SlackNotify;
use Illuminate\Console\Events\ArtisanStarting;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use AlfredNutileInc\LarScanner\Console\SensioLabsRemoteScanCommand;

class LarScannerProvider extends \Illuminate\Support\ServiceProvider
{

    protected $commands = [
        'Sensio' => 'command.sensio',
        'SensioRemote' => 'command.sensio_remote',
    ];


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->registerEvents();
        $this->registerCommands();
    }

    private function registerEvents()
    {
        Event::listen('security.results', function ($from, $results) {

            try {
                (new SlackNotify())->handle($from, $results);
            } catch (\Exception $e) {
                Log::debug(sprintf("Error sending slack message %s", $e->getMessage()));
            }
        });
    }

    private function registerCommands()
    {
        foreach (array_keys($this->commands) as $command) {
            $method = "register{$command}Command";

            call_user_func_array([$this, $method], []);
        }

        $this->commands(array_values($this->commands));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerSensioCommand()
    {
        $this->app->singleton('command.sensio', function ($app) {
            return new SensioScanCommand();
        });
    }

    protected function registerSensioRemoteCommand()
    {
        $this->app->singleton('command.sensio_remote', function ($app) {
            return new SensioLabsRemoteScanCommand();
        });
    }
}
