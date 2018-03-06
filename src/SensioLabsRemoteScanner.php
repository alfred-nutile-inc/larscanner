<?php

namespace AlfredNutileInc\LarScanner;

use Illuminate\Support\Facades\File;
use SensioLabs\Security\SecurityChecker;
use SensioLabs\Security\Crawler;
use GuzzleHttp\Client;

class SensioLabsRemoteScanner extends BaseSensioScanner
{

    protected $name = "SensioLabs Remote Scanner";

    protected $composer_lock_path = "/";

    protected $config = [];

    protected $branch = null;

    /**
     * @var SecurityChecker
     */
    protected $checker;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        try {
            $this->getBranch();

            if (!$this->branch) {
                $message = sprintf("Could not find master or mainline on this repo %s", $this->composer_lock_path);
                throw new \Exception($message);
            }

            $this->getComposerLock();

            $this->results = $this->getChecker()
                ->check($this->getComposerLockPath());

            $this->transformMessage();

            /**
             * Trigger an event for Security messages and pass this results
             * unless empty
             */

            $this->triggerNotificationEvent();
        } catch (\Exception $e) {
            \Log::error(sprintf("Error Getting Composer from Repository %s", $this->composer_lock_path));
            \Log::error($e);
        }
    }

    protected function getBranch()
    {
        $url = $this->getReposFromGitUrl($this->composer_lock_path);
        $url = sprintf(
            "%s/branches",
            $url
        );
        $branches = $this->client->get($url, $this->returnAuthParams());
        $this->branch = $this->getMainBranch(json_decode($branches->getBody(), true));
    }

    protected function returnAuthParams(array $defaults = [])
    {
        $username = config("services.security_scanner.user");
        $token = config("services.security_scanner.key");

        $auth = [
            'query' => [
                'client_id' => $username,
                'client_secret' => $token
            ]
        ];

        return array_merge($defaults, $auth);
    }

    public function getComposerLock()
    {
        $url = $this->getReposFromGitUrl($this->composer_lock_path);
        $url = sprintf(
            "%s/contents/composer.lock",
            $url
        );
        $results = $this->client->get($url, $this->returnAuthParams(['ref' => $this->branch]));
        $results = json_decode($results->getBody(), true);
        $name = str_random(35);
        $this->composer_lock_path = "/tmp/{$name}.lock";
        \File::put($this->composer_lock_path, base64_decode($results['content']));
    }

    public function getMainBranch(array $branches)
    {
        $branch = collect($branches)->first(function ($item) {
            return in_array($item['name'], ['master', 'mainline']);
        });

        return array_get($branch, 'name', null);
    }

    public function getReposFromGitUrl($url)
    {
        $url_to_array = parse_url($url);
        $url = sprintf(
            "https://api.github.com/repos%s",
            array_last($url_to_array)
        );
        return $url;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setBranch($branch)
    {
        $this->branch = $branch;
        return $this;
    }

    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }
}
