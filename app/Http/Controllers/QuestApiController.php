<?php

namespace App\Http\Controllers;

use App\Models\Quest;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class QuestApiController extends Controller
{
    /**
     * Assign a quest to a participant.
     */
    public function give(Request $request, Quest $quest): JsonResponse
    {

        $editionId = $request->input('edition');
        var_dump($request->get('quest_code'));
        $participant = Participant::firstWhere('quest_code', $request->get('quest_code'));
        var_dump($participant);

        if (!$participant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Participant not found!',
            ], 404);
        }

        $enrollment = $participant->enrollments()->where('edition_id', $editionId)->first();

        if ($enrollment === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Participant not enrolled in this edition!',
            ], 400);
        }

        /** @var User|null */
        $user = $request->user();

        if ($user === null) {
            Log::warning('Unauthenticated user attempted to give quest to participant');
            return response()->json([
                'status' => 'error',
                'message' => 'Please log in to perform this action.',
            ], 401);
        }

        if ($user->cannot('give', [$quest, $enrollment])) {
            Log::warning('Current user is not allowed to give quest "{quest}" to user {user}', [
                'quest' => $quest->name,
                'user' => $enrollment->participant->usertype->name,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Not authorized to assign this quest to the participant!',
            ], 403);
        }

        $enrollment->quests()->attach($quest);
        Log::info('Quest {quest} given to user {user}', [
            'quest' => $quest->name,
            'user' => $enrollment->participant->usertype->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Quest assigned successfully!',
        ], 200);
    }
}
