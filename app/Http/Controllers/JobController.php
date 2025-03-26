<?php

namespace App\Http\Controllers;

use App\Services\JobFilterService;
use Illuminate\Http\Request;

class JobController extends Controller
{
    protected $jobFilterService;

    public function __construct(JobFilterService $jobFilterService)
    {
        $this->jobFilterService = $jobFilterService;
    }

    public function index(Request $request)
    {
        $jobs = $this->jobFilterService->applyFilters($request);
        return response()->json($jobs);
    }
}
