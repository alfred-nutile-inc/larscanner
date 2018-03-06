<?php

namespace AlfredNutileInc\LarScanner\Console;

use AlfredNutileInc\LarScanner\ResultDTO;
use AlfredNutileInc\LarScanner\SensioLabsScanner;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use AlfredNutileInc\LarScanner\SensioLabsRemoteScanner;
use function GuzzleHttp\json_encode;

class SensioLabsRemoteScanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larscanner:sensio_remote {github_http_url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check a remote composer lock file';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param SensioLabsScanner $scanner
     * @return mixed
     */
    public function handle(SensioLabsRemoteScanner $scanner)
    {
        try {
            $this->info("Runing report for URL");
            $scanner->setComposerLockPath($this->argument("github_http_url"))->handle();
            if ($scanner->getResults()) {
                foreach ($scanner->getResults() as $result) {
                    $this->error(sprintf(
                        "Security Issues found %s %s %s",
                        $result->title,
                        $result->body,
                        $result->library
                    ));
                }
            } else {
                $this->info("No volnerabilities found");
            }
        } catch (\Exception $e) {
            Log::debug(sprintf(
                "Error running sensio labs scanner %s",
                $e->getMessage()
            ));
        }
    }
}
