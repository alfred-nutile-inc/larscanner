<?php

namespace AlfredNutileInc\LarScanner;

use AlfredNutileInc\LarScanner\SensioLabsScanner;
use Mockery;
use PHPUnit_Framework_Assert;
use function GuzzleHttp\json_decode;
use GuzzleHttp\Client;

class SensioLabsRemoteScannerTest extends TestCase
{
    public function testFullPrivateRun()
    {
        $this->markTestSkipped("This was just to work out the API and is not needed");
        $service = new SensioLabsRemoteScanner(new Client());
        //$url = "https://github.com/alnutile/blog";
        $url = "https://github.com/alnutile/security-scanner-show-error-poc";
        $service->setComposerLockPath($url)->handle();
    }

    public function testGetMainBranch()
    {
        $branches = json_decode(\File::get(__DIR__ . '/../tests/fixtures/branches.json'), true);
        $service = new SensioLabsRemoteScanner(new Client());
        $result = $service->getMainBranch($branches);
        $this->assertEquals("master", $result);
    }

    public function testGetUrl()
    {
        $url = "https://github.com/alnutile/blog";
        $service = new SensioLabsRemoteScanner(new Client());
        $results = $service->getReposFromGitUrl($url);
        $this->assertEquals("https://api.github.com/repos/alnutile/blog", $results);
    }

    public function testGettingComposerFile()
    {
        $this->markTestSkipped("This was just to work out the API and is not needed");
        $service = new SensioLabsRemoteScanner(new Client());
        $url = "https://github.com/alnutile/blog";
        $results = $service->setBranch("master")->setComposerLockPath($url)->getComposerLock();
        $this->assertFileExists($service->getComposerLockPath());
    }
}
