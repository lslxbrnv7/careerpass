<?php

namespace App\Http\Controllers\Job;

use Mockery\MockInterface;
use Tests\TestCase;
use App\Models\Job;
use Carbon\Carbon;

/**
 * @coversDefaultClass  \App\Http\Controllers\Job\JobController
 */
class IndexRequestTest extends TestCase
{
    /**
     * @covers ::index
     * @covers \App\Http\Controllers\Job\IndexRequest::rules
     * @covers \App\Http\Controllers\Job\IndexRequest::search
     */
    public function test_can_view()
    {
        $job = Job::factory()->create();

        $systemUnderTest = $this->actingAs($this->user)->get(route('jobs.index'));
        $systemUnderTest->assertJsonPath('data.0.title', $job->title);
    }

    /**
     * @covers ::index
     * @covers \App\Http\Controllers\Job\IndexRequest::rules
     * @covers \App\Http\Controllers\Job\IndexRequest::search
     */
    public function test_can_view_search_title()
    {
        $job = Job::factory([
            'title' => 'a random title'
        ])->create();
        $job2 = Job::factory([
            'title' => 'another random title'
        ])->create();

        $systemUnderTest = $this->actingAs($this->user)->get(route('jobs.index', ['title' => 'a random']));
        $systemUnderTest->assertJsonCount(1, 'data');
        $systemUnderTest->assertJsonPath('data.0.title', $job->title);
    }

    /**
     * @covers ::index
     * @covers \App\Http\Controllers\Job\IndexRequest::rules
     * @covers \App\Http\Controllers\Job\IndexRequest::search
     */
    public function test_can_view_search_company()
    {
        $job = Job::factory([
            'company' => 'a random company'
        ])->create();
        $job2 = Job::factory([
            'company' => 'another random company'
        ])->create();

        $systemUnderTest = $this->actingAs($this->user)->get(route('jobs.index', ['company' => 'a random']));
        $systemUnderTest->assertJsonCount(1, 'data');
        $systemUnderTest->assertJsonPath('data.0.company', $job->company);
    }

    /**
     * @covers ::index
     * @covers \App\Http\Controllers\Job\IndexRequest::rules
     * @covers \App\Http\Controllers\Job\IndexRequest::search
     */
    public function test_can_view_search_active()
    {
        $job = Job::factory()->active()->create();
        $job2 = Job::factory([
            'is_active' => false
        ])->create();

        $systemUnderTest = $this->actingAs($this->user)->get(route('jobs.index', ['is_active' => 1]));
        $systemUnderTest->assertJsonCount(1, 'data');
        $systemUnderTest->assertJsonPath('data.0.id', $job->id);
    }

    /**
     * @covers ::index
     * @covers \App\Http\Controllers\Job\IndexRequest::rules
     * @covers \App\Http\Controllers\Job\IndexRequest::search
     */
    public function test_can_view_search_not_active()
    {
        $job = Job::factory()->active()->create();
        $job2 = Job::factory([
            'is_active' => false
        ])->create();

        $systemUnderTest = $this->actingAs($this->user)->get(route('jobs.index', ['is_active' => 0]));
        $systemUnderTest->assertJsonCount(1, 'data');
        $systemUnderTest->assertJsonPath('data.0.id', $job2->id);
    }
}
