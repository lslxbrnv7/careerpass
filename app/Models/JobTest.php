<?php

namespace App\Models;

use Mockery\MockInterface;
use Tests\TestCase;
use Carbon\Carbon;
use App\Tasks\JobStatusUpdater;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;

/**
 * @coversDefaultClass  \App\Models\Job
 */
class JobTest extends TestCase
{
    /**
     * @covers \App\Observers\JobStatusObserver::created
     * @covers \App\Observers\JobStatusObserver::createTask
     */
    public function test_create_runs_automation()
    {
        $job = Job::factory()->create();
        Queue::assertPushed(JobStatusUpdater::class, function ($task) use ($job) {
            return $job->id === $task->_job->id;
        });
    }

    /**
     * @covers \App\Observers\JobStatusObserver::updated
     * @covers \App\Observers\JobStatusObserver::createTask
     */
    public function test_update_runs_automation()
    {
        $job = Job::factory()->createQuietly();
        $job->title = $this->faker->name();
        $job->save();
        Queue::assertPushed(JobStatusUpdater::class, function ($task) use ($job) {
            return $job->id === $task->_job->id;
        });
    }

    /**
     * @covers \App\Observers\SlugObserver::creating
     * @covers \App\Observers\SlugObserver::updateSlug
     * @covers \App\Observers\SlugObserver::calculateSlug
     * @covers \App\Observers\JobStatusObserver::createTask
     */
    public function test_creating_generates_slug()
    {
        $job = Job::factory()->create();
        $this->assertEquals($job->slug, Str::slug($job->title));
    }

    /**
     * @covers \App\Observers\SlugObserver::updating
     * @covers \App\Observers\SlugObserver::updateSlug
     * @covers \App\Observers\SlugObserver::calculateSlug
     * @covers \App\Observers\JobStatusObserver::createTask
     */
    public function test_update_generates_slug()
    {
        $job = Job::factory()->createQuietly();
        $job->title = $this->faker->name();
        $job->save();
        $this->assertEquals($job->slug, Str::slug($job->title));
    }

    /**
     * @covers \App\Observers\SlugObserver::updating
     * @covers \App\Observers\SlugObserver::updateSlug
     * @covers \App\Observers\SlugObserver::calculateSlug
     * @covers \App\Observers\JobStatusObserver::createTask
     */
    public function test_reusing_name_appends_suffix()
    {
        $title = $this->faker->name();
        $job = Job::factory([
            'title' => $title
        ])->create();
        $job2 = Job::factory([
            'title' => $title
        ])->create();
        $this->assertEquals($job2->slug, Str::slug($title) . "-1");
    }

    /**
     * @covers ::scopePending
     */
    public function test_can_get_pending_jobs()
    {
        $job = Job::factory([
            'status' => Job::STATUS_PENDING,
        ])->createQuietly();
        $job2 = Job::factory([
            'status' => Job::STATUS_EXPIRED,
        ])->createQuietly();

        $this->assertEquals(1, Job::query()->pending()->get()->count());
    }

    /**
     * @covers ::scopeExpired
     */
    public function test_can_get_expired_jobs()
    {
        $title = $this->faker->name();
        $job = Job::factory([
            'status' => Job::STATUS_EXPIRED,
        ])->createQuietly();
        $job2 = Job::factory([
            'status' => Job::STATUS_PENDING,
        ])->createQuietly();
        $this->assertEquals(1, Job::query()->expired()->get()->count());
    }

    /**
     * @covers ::scopeActive
     */
    public function test_can_get_active_jobs()
    {
        $title = $this->faker->name();
        $job = Job::factory([
            'status' => Job::STATUS_ACTIVE,
        ])->createQuietly();
        $job2 = Job::factory([
            'status' => Job::STATUS_PENDING,
        ])->createQuietly();

        $this->assertEquals(1, Job::query()->active()->get()->count());
    }
}
