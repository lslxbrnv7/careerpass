<?php

namespace App\Http\Controllers\Job;

use Mockery\MockInterface;
use Tests\TestCase;
use App\Models\Job;
use Carbon\Carbon;

/**
 * @coversDefaultClass  \App\Http\Controllers\Job\JobController
 */
class StoreRequestTest extends TestCase
{
    /**
     * @covers ::store
     * @covers \App\Http\Controllers\Job\StoreRequest::rules
     */
    public function test_cannot_store_without_credentials()
    {
        $systemUnderTest = $this->postJson(route('jobs.store'));
        $systemUnderTest->assertUnauthorized();
    }

    /**
     * @covers ::store
     * @covers \App\Http\Controllers\Job\StoreRequest::rules
     * @covers \App\Http\Controllers\Job\StoreRequest::submit
     */
    public function test_cannot_store_with_no_params()
    {
        $systemUnderTest = $this->actingAs($this->user)->postJson(route('jobs.store'));
        $systemUnderTest->assertInvalid([
            'title', 'company', 'wysiwyg', 'owner', 'starts_at', 'expires_at', 'is_active'
        ]);
    }

    /**
     * @covers ::store
     * @covers \App\Http\Controllers\Job\StoreRequest::rules
     * @covers \App\Http\Controllers\Job\StoreRequest::submit
     */
    public function test_can_store_with_full_params()
    {
        $title = $this->faker->text(5000);
        $company = $this->faker->name();
        $owner = $this->faker->name();
        $wysiwyg = "<p>Test</p>";
        $start = Carbon::createMidnightDate();
        $expire = Carbon::createMidnightDate()->modify('+1 month');
        $active = 1;

        $systemUnderTest = $this->actingAs($this->user)->postJson(route('jobs.store'), [
            'title' => $title,
            'company' => $company,
            'owner' => $owner,
            'wysiwyg' => $wysiwyg,
            'starts_at' => $start,
            'expires_at' => $expire,
            'is_active' => $active
        ]);
        $systemUnderTest->assertStatus(201);
        $systemUnderTest->assertJsonPath('title', $title);
        $systemUnderTest->assertJsonPath('company', $company);
        $systemUnderTest->assertJsonPath('owner', $owner);
        $systemUnderTest->assertJsonPath('wysiwyg', $wysiwyg);
        $systemUnderTest->assertJsonPath('starts_at', $start->toISOString());
        $systemUnderTest->assertJsonPath('expires_at', $expire->toISOString());
        $systemUnderTest->assertJsonPath('is_active', $active);
    }

    /**
     * @covers ::store
     * @covers \App\Http\Controllers\Job\StoreRequest::rules
     * @covers \App\Http\Controllers\Job\StoreRequest::submit
     */
    public function test_cannot_store_with_invalid_dates()
    {
        $title = $this->faker->name();

        $systemUnderTest = $this->actingAs($this->user)->postJson(route('jobs.store'), [
            'title' => $title,
            'company' => $this->faker->name(),
            'wysiwyg' => "<p>Test</p>",
            'starts_at' => Carbon::now()->modify('+1 month'),
            'expires_at' => Carbon::now(),
            'is_active' => 1
        ]);
        $systemUnderTest->assertInvalid([
            'starts_at', 'expires_at'
        ]);
    }

    /**
     * @covers ::update
     * @covers \App\Http\Controllers\Job\StoreRequest::rules
     */
    public function test_cannot_update_without_credentials()
    {
        $job = Job::factory()->create();

        $systemUnderTest = $this->patchJson(route('jobs.update', ['job' => $job]));
        $systemUnderTest->assertUnauthorized();
    }

    /**
     * @covers ::update
     * @covers \App\Http\Controllers\Job\StoreRequest::rules
     * @covers \App\Http\Controllers\Job\StoreRequest::submit
     */
    public function test_cannot_update_with_no_params()
    {
        $job = Job::factory()->create();

        $systemUnderTest = $this->actingAs($this->user)->patchJson(route('jobs.update', ['job' => $job]));
        $systemUnderTest->assertInvalid([
            'title', 'company', 'wysiwyg', 'owner', 'starts_at', 'expires_at', 'is_active'
        ]);
    }

    /**
     * @covers ::update
     * @covers \App\Http\Controllers\Job\StoreRequest::rules
     * @covers \App\Http\Controllers\Job\StoreRequest::submit
     */
    public function test_can_update_with_full_params()
    {
        $job = Job::factory()->create();
        $title = $this->faker->text(5000);
        $company = $this->faker->name();
        $owner = $this->faker->name();
        $wysiwyg = "<p>Test123141</p>";
        $start = Carbon::createMidnightDate();
        $expire = Carbon::createMidnightDate()->modify('+1 month');
        $active = 1;

        $systemUnderTest = $this->actingAs($this->user)->patchJson(route('jobs.update', ['job' => $job]), [
            'title' => $title,
            'company' => $company,
            'owner' => $owner,
            'wysiwyg' => $wysiwyg,
            'starts_at' => $start,
            'expires_at' => $expire,
            'is_active' => $active
        ]);
        $systemUnderTest->assertStatus(200);
        $systemUnderTest->assertJsonPath('title', $title);
        $systemUnderTest->assertJsonPath('company', $company);
        $systemUnderTest->assertJsonPath('owner', $owner);
        $systemUnderTest->assertJsonPath('wysiwyg', $wysiwyg);
        $systemUnderTest->assertJsonPath('starts_at', $start->toISOString());
        $systemUnderTest->assertJsonPath('expires_at', $expire->toISOString());
        $systemUnderTest->assertJsonPath('is_active', $active);
    }

    /**
     * @covers ::update
     * @covers \App\Http\Controllers\Job\StoreRequest::rules
     * @covers \App\Http\Controllers\Job\StoreRequest::submit
     */
    public function test_cannot_update_with_invalid_dates()
    {
        $job = Job::factory()->create();
        $title = $this->faker->name();

        $systemUnderTest = $this->actingAs($this->user)->patchJson(route('jobs.update', ['job' => $job]), [
            'title' => $title,
            'company' => $this->faker->name(),
            'wysiwyg' => "<p>Test</p>",
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->modify('-1 month'),
            'is_active' => 1
        ]);
        $systemUnderTest->assertInvalid([
            'starts_at', 'expires_at'
        ]);
    }
}
