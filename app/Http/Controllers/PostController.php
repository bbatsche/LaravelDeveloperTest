<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\PostData;
use App\Repository\PostRepository;
use Illuminate\Http\JsonResponse;

final class PostController extends Controller
{
    public function __construct(
        private PostRepository $repo,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json($this->repo->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(PostData $post): JsonResponse
    {
        return response()->json($post);
    }
}
