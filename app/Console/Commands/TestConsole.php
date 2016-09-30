<?php

namespace App\Console\Commands;

use App\Artefact;
use App\ArtefactTypeAttribute;
use App\ConditionalReport;
use App\ConditionalReportsSegment;
use App\User;
use Excel;
use Illuminate\Console\Command;
use Mail;
use Queue;

class TestConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        Mail::raw("Hello World", function ($message) {
            //
            $message
                ->to("poosan9@gmail.com", "Poovarasan")
                ->subject(env('APP_NAME') . 'Password Reset!');
        });
    }
}
