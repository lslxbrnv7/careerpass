<?php

namespace App\Http\Controllers\Job;

use Mockery\MockInterface;
use Tests\TestCase;
use App\Models\Job;
use Carbon\Carbon;

/**
 * @coversDefaultClass  \App\Http\Controllers\Job\JobController
 */
class JobControllerTest extends TestCase
{
    /**
     * @covers ::index
     */
    public function test_cannot_index_without_credentials()
    {
        $systemUnderTest = $this->getJson(route('jobs.index'));
        $systemUnderTest->assertUnauthorized();
    }

    /**
     * @covers ::index
     */
    public function test_can_view_index()
    {
        $job = Job::factory()->create();
        $systemUnderTest = $this->actingAs($this->user)->getJson(route('jobs.index'));
        $systemUnderTest->assertJsonPath('data.0.title', $job->title);
    }

    /**
     * @covers ::show
     */
    public function test_cannot_show_without_credentials()
    {
        $job = Job::factory()->create();
        $systemUnderTest = $this->getJson(route('jobs.show', ['job' => $job]));
        $systemUnderTest->assertUnauthorized();
    }

    /**
     * @covers ::show
     */
    public function test_can_view_show()
    {
        $job = Job::factory()->create();
        $systemUnderTest = $this->actingAs($this->user)->getJson(route('jobs.show', ['job' => $job]));
        $systemUnderTest->assertJsonPath('title', $job->title);
    }

    /**
     * @covers ::destroy
     */
    public function test_can_view_destroy()
    {
        $job = Job::factory()->create();
        $systemUnderTest = $this->deleteJson(route('jobs.destroy', ['job' => $job]));
        $systemUnderTest->assertOk();
    }
}
