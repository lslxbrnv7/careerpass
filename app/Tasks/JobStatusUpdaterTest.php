<?php

namespace App\Tasks;

use Mockery\MockInterface;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;
use App\Models\Job;

/**
 * @coversDefaultClass  \App\Tasks\JobStatusUpdater
 */
class JobStatusUpdaterTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::handle
     * @covers ::getStatus
     */
    public function test_sets_pending_status()
    {
        $job = Job::factory([
            'status' => Job::STATUS_ACTIVE,
            'is_active' => true,
            'starts_at' => Carbon::now()->modify('+1 month'),
            'expires_at' => Carbon::now()->modify('+2 months')
        ])->createQuietly();
        $systemUnderTest = new JobStatusUpdater($job);
        $systemUnderTest->handle();
        $this->assertEquals(Job::STATUS_PENDING, $job->status);
    }

    /**
     * @covers ::__construct
     * @covers ::handle
     * @covers ::getStatus
     */
    public function test_sets_expired_status_for_flag()
    {
        $job = Job::factory([
            'status' => Job::STATUS_ACTIVE,
            'is_active' => false
        ])->createQuietly();
        $systemUnderTest = new JobStatusUpdater($job);
        $systemUnderTest->handle();
        $this->assertEquals(Job::STATUS_EXPIRED, $job->status);
    }

    /**
     * @covers ::__construct
     * @covers ::handle
     * @covers ::getStatus
     */
    public function test_sets_expired_status_for_dates()
    {
        $job = Job::factory([
            'status' => Job::STATUS_ACTIVE,
            'is_active' => true,
            'starts_at' => Carbon::now()->modify('-2 months'),
            'expires_at' => Carbon::now()->modify('-1 month')
        ])->createQuietly();
        $systemUnderTest = new JobStatusUpdater($job);
        $systemUnderTest->handle();
        $this->assertEquals(Job::STATUS_EXPIRED, $job->status);
    }

    /**
     * @covers ::__construct
     * @covers ::handle
     * @covers ::getStatus
     */
    public function test_sets_active_status()
    {
        $job = Job::factory([
            'status' => Job::STATUS_PENDING,
            'is_active' => true,
            'starts_at' => Carbon::now()->modify('-2 months'),
            'expires_at' => Carbon::now()->modify('+2 months')
        ])->createQuietly();
        $systemUnderTest = new JobStatusUpdater($job);
        $systemUnderTest->handle();
        $this->assertEquals(Job::STATUS_ACTIVE, $job->status);
    }

    /**
     * @covers ::__construct
     * @covers ::handle
     * @covers ::createTask
     */
    public function test_creates_further_tasks()
    {
        $start = Carbon::now()->modify('+1 months');
        $expire = Carbon::now()->modify('+2 months');
        $job = Job::factory([
            'status' => Job::STATUS_PENDING,
            'is_active' => true,
            'starts_at' => $start,
            'expires_at' => $expire
        ])->createQuietly();
        $systemUnderTest = new JobStatusUpdater($job);
        $systemUnderTest->handle();

        Queue::assertPushed(JobStatusUpdater::class, function ($task) use ($job, $start) {
            return $job->id === $task->_job->id && $start->toDateString() === $task->delay->toDateString();
        });
        Queue::assertPushed(JobStatusUpdater::class, function ($task) use ($job, $expire) {
            return $job->id === $task->_job->id && $expire->toDateString() === $task->delay->toDateString();
        });
    }

    /**
     * @covers ::__construct
     * @covers ::handle
     * @covers ::createTask
     */
    public function test_doesnt_create_further_tasks_in_past()
    {
        $start = Carbon::now()->modify('-2 months');
        $expire = Carbon::now()->modify('-1 months');
        $job = Job::factory([
            'status' => Job::STATUS_PENDING,
            'is_active' => true,
            'starts_at' => $start,
            'expires_at' => $expire
        ])->createQuietly();
        $systemUnderTest = new JobStatusUpdater($job);
        $systemUnderTest->handle();
        Queue::assertNotPushed(JobStatusUpdater::class, function ($task) use ($job, $start) {
            return $job->id === $task->_job->id && $start->toDateString() === $task->delay->toDateString();
        });
        Queue::assertNotPushed(JobStatusUpdater::class, function ($task) use ($job, $expire) {
            return $job->id === $task->_job->id && $expire->toDateString() === $task->delay->toDateString();
        });
    }
}
