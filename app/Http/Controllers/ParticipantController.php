<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Inertia\Inertia;

class ParticipantController extends Controller
{
    public function show(Participant $participant)
    {
        return Inertia::render('Profile/Show', [
            'participant' => $participant->load('user')->toArray(),
        ]);
    }
}
