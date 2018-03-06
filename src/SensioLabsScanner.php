<?php

namespace AlfredNutileInc\LarScanner;

use Illuminate\Support\Facades\File;
use SensioLabs\Security\SecurityChecker;

class SensioLabsScanner extends BaseSensioScanner
{

    protected $name = "SensioLabs Scanner";

    protected $composer_lock_path = "/";

    /**
     * @var SecurityChecker
     */
    protected $checker;


    public function handle()
    {
        $this->results = $this->getChecker()
            ->check($this->getComposerLockPath());


        $this->results = $this->getChecker()
            ->check($this->getComposerLockPath());

        $this->transformMessage();

        /**
         * Trigger an event for Security messages and pass this results
         * unless empty
         */

        $this->triggerNotificationEvent();
    }
}
