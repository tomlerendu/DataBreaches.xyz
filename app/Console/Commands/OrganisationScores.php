<?php

namespace App\Console\Commands;

use App\Models\Organisation;
use Illuminate\Console\Command;

class OrganisationScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'organisations:recalculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculates the scores for each organisation.';

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
        Organisation::chunk(30, function($organisations) {
           foreach ($organisations as $organisation) {
               $organisation->calculateBreaches();
               $organisation->calculateScore();
               $organisation->save();
           }
        });
    }
}
