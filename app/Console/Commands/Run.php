<?php

namespace App\Console\Commands;

use App\Artefact;
use Illuminate\Console\Command;

class Run extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To Run the Project';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
//        $this->info("Starting Application");
//        \Artisan::call('serve --port '.env('APP_PORT','80'));

        $this->info("Starting Scheduler");
        \Artisan::call('schedule:run');
    }
}
