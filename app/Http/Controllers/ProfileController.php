<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\ProfileData;
use App\Repository\ProfileRepository;
use Illuminate\Http\JsonResponse;

final class ProfileController extends Controller
{
    public function __construct(
        private ProfileRepository $repo,
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
    public function show(ProfileData $profile): JsonResponse
    {
        return response()->json($profile);
    }
}
