<?php

namespace App\Console\Commands;

use App\Models\Organisation;
use App\Models\Tag;
use Illuminate\Console\Command;

class TagCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tags:recalculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Determines how many organisations are in each tag.';

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
        Tag::chunk(5, function($tags) {
            foreach ($tags as $tag) {
                //Find all relevant accepted organisations
                $count = Organisation
                    ::whereHas('tags', function ($query) use ($tag) {
                        $query->where('id', $tag->id);
                    })
                    ->status(['Accepted'])
                    ->count();

                $tag->organisation_count = $count;

                $tag->save();
            }
        });
    }
}
