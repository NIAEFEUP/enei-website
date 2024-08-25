<?php

namespace App\Http\Controllers;

use App\Models\Edition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
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

        $sponsorTiers = $edition->sponsor_tiers()->with(['sponsors' => ['company' => ['user']]])->get();
        $speakers = $edition->speakers()->get();
        $days = $edition->event_days()->orderBy('date', 'ASC')->get();

        // TODO: see if this can be done with the old methods
        // $talk_count = $edition->talks()->count();
        // $activity_count = $edition->workshops()->count();
        $talk_count = $edition->events()->talk()->count();
        $activity_count = $edition->events()->activity()->count();

        $stand_count = $edition->stands()->count();

        $can_enroll = Gate::allows('enroll', $edition);

        // FIXME: This code is currently in the home page,
        // where should the referral redirect to?
        $referral_code = $request->input('ref');
        if ($referral_code != null) {
            // XXX: Cookie is stored forever, the limit is 400 days,
            // browsers seem to cap it later
            Cookie::queue(Cookie::forever('link_referral', $referral_code));
        }

        return Inertia::render('Home', [
            'edition' => $edition,
            'sponsorTiers' => $sponsorTiers,
            'speakers' => $speakers,
            'activityCount' => $activity_count,
            'talkCount' => $talk_count,
            'days' => $days,
            'standCount' => $stand_count,
            'canEnroll' => $can_enroll,
        ]);
    }
}
