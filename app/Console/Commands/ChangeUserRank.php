<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserRankEnum;
use Illuminate\Console\Command;

class ChangeUserRank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:rank';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates a users rank.';

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
        if (!$this->hasArgument('username')) {
            $this->error('Missing username argument.');
        }

        if (!$this->hasArgument('rank') && in_array($this->argument('rank'), ['user', 'editor'])) {
            $this->error('Missing or invalid rank argument');
        }

        $username = $this->argument('username');
        $rank = $this->argument('rank');

        $user = User::where('username', $username)->firstOrFail();
        $user->rank = $rank == 'editor' ? UserRankEnum::Editor : UserRankEnum::User;
        $user->save();

        $this->info('The users rank was updated to ' . $rank . '.');
    }
}
