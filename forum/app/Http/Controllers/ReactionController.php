<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReactionRequest;
use App\Services\ReactionService;
use Illuminate\Http\JsonResponse;

class ReactionController extends Controller
{
    public function __construct(
        private ReactionService $reactionService,
    ) {}

    public function toggle(ReactionRequest $request): JsonResponse
    {
        $result = $this->reactionService->toggle(
            auth()->user(),
            $request->input('target_type'),
            (int) $request->input('target_id'),
        );

        return response()->json($result);
    }
}
