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

        $editionId = $request->input('edition')->id;
        $participant = Participant::firstWhere('quest_code', $request->get('quest_code'));

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

        $enrollment->quests()->attach($quest);


        return response()->json([
            'status' => 'success',
            'message' => 'Quest assigned successfully!',
        ], 200);
    }
}
