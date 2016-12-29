<?php

use AlfredNutileInc\LarScanner\SensioLabsScanner;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;

class SensioLabsScannerTest extends TestCase
{
    public function test_how_to_schedule() {

        $this->expectsEvents('security.results');

        $mocked = Mockery::mock(\SensioLabs\Security\SecurityChecker::class);
        $results = json_decode(File::get(__DIR__ . '/fixtures/failed_results.json'), true);
        $mocked->shouldReceive('check')->andReturn($results);
        $scanner = new SensioLabsScanner();
        $scanner->setChecker($mocked);
        $scanner->setComposerLockPath(base_path())->handle();

        /**
         * Transform the results to a generic form
         * then pass to messenger
         * then send to slack and mail
         */
        PHPUnit_Framework_Assert::assertCount(2, $scanner->getResults());

        PHPUnit_Framework_Assert::assertTrue(is_a($scanner->getResults()[0],
            \AlfredNutileInc\LarScanner\ResultDTO::class), "Oops not using the correct object here");
    }

}