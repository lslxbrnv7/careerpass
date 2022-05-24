<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker, DatabaseTransactions;

    protected $user;

    public function setUp() : void
    {
        parent::setUp();
        $this->user = User::where('email', 'test@laravel-test.com')->first();
        Queue::fake();
    }

    public function tearDown() : void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }
}
