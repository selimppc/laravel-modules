<?php

namespace Modules\Example\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Example\Repositories\Contracts\ExampleRepository;

final class ExampleController extends Controller
{
    public function __construct(private ExampleRepository $repo) {}

    public function index()
    {
        return view('example::index', [
            'items' => $this->repo->latest(5),
        ]);
    }

    public function apiList(): JsonResponse
    {
        return response()->json([
            'data' => $this->repo->latest(5),
        ]);
    }
}
