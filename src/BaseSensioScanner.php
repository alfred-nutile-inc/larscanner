<?php


namespace AlfredNutileInc\LarScanner;

use Illuminate\Support\Facades\Log;
use SensioLabs\Security\SecurityChecker;
use SensioLabs\Security\Crawler;

class BaseSensioScanner extends BaseScanner
{
    public function handle()
    {
        //
    }

    public function transformMessage()
    {

        $this->checkResultsFormat();
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

    protected function checkResultsFormat()
    {
        if (!is_array($this->results) && get_class($this->results) === "SensioLabs\Security\Result") {
            $this->results = json_decode($this->results, 128);
        }
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
            $crawler = new Crawler();
            $checker = new SecurityChecker($crawler);
        }

        $this->checker = $checker;
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
}
