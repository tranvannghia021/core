<?php

namespace Devtvn\Social\Commands;

use Illuminate\Console\Command;

class PublicConfigESCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coppy:es';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'coppy config elasticsearch';


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info("starting coppy elasticsearch config");
        $pathVendorEs = base_path("vendor/elasticquent/elasticquent/src/config/elasticquent.php");
        $dirES = config_path('elasticquent.php');
        if (!file_exists($dirES)) {
            file_put_contents($dirES, file_get_contents($pathVendorEs));
        }
        $this->info("copped config elasticsearch completed");

    }
}