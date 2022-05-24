<?php

namespace App\Tasks;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Job;
use Carbon\Carbon;

class JobStatusUpdater implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $_job;

    public function __construct(Job $_job)
    {
        $this->_job = $_job;
    }

    public function handle()
    {
        $this->_job->status = $this->getStatus();
        $this->_job->saveQuietly();
        $this->createTask($this->_job->starts_at);
        $this->createTask($this->_job->expires_at);
    }

    private function getStatus()
    {
        if (!$this->_job->is_active) {
            return Job::STATUS_EXPIRED;
        }

        $today = Carbon::createMidnightDate();
        if ($this->_job->expires_at->lte($today)) {
            return Job::STATUS_EXPIRED;
        }

        if ($this->_job->starts_at->gte($today)) {
            return Job::STATUS_PENDING;
        }

        return Job::STATUS_ACTIVE;
    }

    private function createTask(Carbon $runAt)
    {
        if ($runAt->isPast()) {
            return;
        }
        JobStatusUpdater::dispatch($this->_job)->delay($runAt);
    }
}
