<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Job;

class LoadJobsFactory extends Command
{
    protected $signature = 'factory:load-jobs {count? : The amount of jobs to generate}';
    protected $description = 'Load fake jobs into the database';

    public function handle()
    {
        $count = $this->argument('count') ?? 100;
        echo "Loading $count jobs into db\n";
        Job::factory()->count($count)->create(); 
        echo "Finished loading $count jobs\n";
    }
}
