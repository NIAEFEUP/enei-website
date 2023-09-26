<?php

namespace App\Http\Controllers;

use App\Models\Edition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function show(Request $request)
    {
        /** @var Edition */
        $edition = $request->input('edition');

        if ($edition === null) {
            return response('No edition found', 500);
        }

        $sponsors = $edition->sponsors()->with(['company' => ['user']])->get();
        $speakers = $edition->speakers()->get();
        $days = $edition->event_days()->orderBy('date', 'ASC')->get();

        $event_count = $edition->events()->count();
        $activity_count = $edition->events()->where('capacity', '>', 0)->count();
        $talk_count = $event_count - $activity_count;
        $stand_count = $edition->stands()->count();

        $can_enroll = Gate::allows('enroll', $edition);

        return Inertia::render('Home', [
            'edition' => $edition,
            'sponsors' => $sponsors,
            'speakers' => $speakers,
            'activityCount' => $activity_count,
            'talkCount' => $talk_count,
            'days' => $days,
            'standCount' => $stand_count,
            'canEnroll' => $can_enroll,
        ]);
    }
}
