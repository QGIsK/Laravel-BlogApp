<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\MailtrapExample;
use Illuminate\Support\Facades\Mail;


use App\User;

class sendWeeklyDigest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:digest';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly digest';

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
        $users = User::where('weekly_digest', 1)->get();

        if(empty($users)) return;

        foreach($users as $user) {
            Mail::to($user->email)->send(new MailtrapExample()); 
        }
    }
}
