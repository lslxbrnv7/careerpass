<?php

namespace App\Observers;

use App\Models\Job;
use App\Tasks\JobStatusUpdater;

class JobStatusObserver
{
    public function created(Job $job)
    {
        $this->createTask($job);
    }

    public function updated(Job $job)
    {
        $this->createTask($job);
    }

    private function createTask(Job $job)
    {
        JobStatusUpdater::dispatchSync($job);
    }
}
