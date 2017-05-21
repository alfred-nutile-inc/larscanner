<?php


namespace AlfredNutileInc\LarScanner;

use Illuminate\Support\Facades\Log;

abstract class BaseScanner
{
    public $results;

    protected $name = "LarScanner";

    abstract public function handle();

    abstract public function transformMessage();

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    protected function triggerNotificationEvent()
    {
        if ($this->results) {
            event('security.results', [$this->name, $this->results]);
        } else {
            Log::debug(sprintf("No results for %s", $this->name));
        }
    }
}
