<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $u;
    protected $pwd;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $u,$pwd)
    {
        //
        $this->u = $u;
        $this->pwd = $pwd;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $user = $this->u;
        Mail::send('email.resetpassword', array(
            'username' => $this->u->abhyasiid,
            'password' => $this->pwd,
            'url' => base_path('/')
        ), function ($message) use ($user) {
            $message
                ->to($user->email, $user->fname . " " . $user->lname)
                ->subject(env('APP_NAME') . 'Password Reset!');
        });
    }
}
