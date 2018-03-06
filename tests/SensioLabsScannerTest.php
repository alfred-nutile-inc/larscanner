<?php

namespace AlfredNutileInc\LarScanner;

use AlfredNutileInc\LarScanner\SensioLabsScanner;
use Mockery;
use PHPUnit_Framework_Assert;

class SensioLabsScannerTest extends TestCase
{
    public function testHowToSchedule()
    {
        \Event::fake();
        $mocked = Mockery::mock(\SensioLabs\Security\SecurityChecker::class);
        $results = json_decode(file_get_contents(__DIR__ . '/fixtures/failed_results.json'), true);
        $mocked->shouldReceive('check')->andReturn($results);
        $scanner = new SensioLabsScanner();
        $scanner->setChecker($mocked);
        $base_path = __DIR__;
        $scanner->setComposerLockPath($base_path)->handle();

        $this->assertCount(2, $scanner->getResults());

        $this->assertTrue(is_a(
            $scanner->getResults()[0],
            \AlfredNutileInc\LarScanner\ResultDTO::class
        ), "Oops not using the correct object here");
    }
}
