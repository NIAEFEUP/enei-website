<?php

namespace App\Http\Controllers;

use App\Models\Quest;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class QuestApiController extends Controller
{
    /**
     * Assign a quest to a participant.
     */
    public function give(Request $request, Quest $quest): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'quest_code' => 'required|exists:participants,quest_code',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid input',
                'errors' => $validator->errors(),
            ], 400);
        }
        $editionId = $request->input('edition')->id;
        if ($editionId === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Edition not found!',
            ], 404);
        }
        $participant = Participant::firstWhere('quest_code', $request->get('quest_code'));

        if (!$participant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Participant not found!',
            ], 404);
        };

        $enrollment = $participant->enrollments()->where('edition_id', $editionId)->first();
        if ($enrollment === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Participant not enrolled in this edition!',
            ], 400);
        }

        try {
            $enrollment->quests()->attach($quest);
        } catch (\Exception $e) {
            Log::error("Failed to attach quest (ID: {$quest->id}) to enrollment (ID: {$enrollment->id})", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to assign quest',
            ], 500);
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Quest assigned successfully!',
        ], 200);
    }
}
