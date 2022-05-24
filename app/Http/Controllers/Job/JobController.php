<?php

namespace App\Http\Controllers\Job;

use App\Models\Job;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('destroy');
    }

    public function index(IndexRequest $request)
    {
        return $request->search();
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $job = $request->submit();
            DB::commit();
            return $job;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show(Job $job)
    {
        return $job;
    }

    public function update(StoreRequest $request, Job $job)
    {
        DB::beginTransaction();
        try {
            $job = $request->submit($job);
            DB::commit();
            return $job;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy(Job $job)
    {
        DB::beginTransaction();
        try {
            $job->delete();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
