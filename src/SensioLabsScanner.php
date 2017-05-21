<?php

namespace AlfredNutileInc\LarScanner;

use Illuminate\Support\Facades\File;
use SensioLabs\Security\SecurityChecker;

class SensioLabsScanner extends BaseScanner
{

    protected $name = "SensioLabs Scanner";

    protected $composer_lock_path = "/";

    /**
     * @var SecurityChecker
     */
    protected $checker;


    public function handle()
    {
        $this->results = $this->getChecker()->check($this->getComposerLockPath());

        $this->transformMessage();

        /**
         * Trigger an event for Security messages and pass this results
         * unless empty
         */

        $this->triggerNotificationEvent();
    }

    public function transformMessage()
    {

        if ($this->results) {
            $results = $this->results;
            $this->results = [];
            foreach ($results as $library => $result) {
                foreach ($result['advisories'] as $advisory) {
                    $this->results[] = new ResultDTO($advisory['title'], $advisory['link'], $library);
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getComposerLockPath()
    {
        return $this->composer_lock_path;
    }

    /**
     * @param string $composer_lock_path
     * @return $this
     */
    public function setComposerLockPath($composer_lock_path)
    {
        $this->composer_lock_path = $composer_lock_path;
        return $this;
    }

    /**
     * @return SecurityChecker
     */
    public function getChecker()
    {
        if (!$this->checker) {
            $this->setChecker();
        }
        return $this->checker;
    }

    /**
     * @param SecurityChecker $checker
     */
    public function setChecker($checker = null)
    {
        if ($checker == null) {
            $checker = new SecurityChecker();
        }

        $this->checker = $checker;
    }
}
