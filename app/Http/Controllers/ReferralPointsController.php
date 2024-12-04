<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\User;
use App\Services\HashIdService;

class ReferralPointsController extends Controller
{
    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public static function AttributePoints(Participant $referred_participant, string $referral_code)
    {
        if (! is_null($referral_code)) {
            $referral_user_id = (new HashIdService)->decode($referral_code);

            $referral_user = User::firstWhere("id", '=', $referral_user_id);

            if (is_null($referral_user)) {
                return;
            }

            if ($referral_user->isStudentAssociation()) {
                // $referred_participant->promoter = $user->id;
                dump("referred", $referred_participant->attributesToArray());
                dump("referral", $referral_user->attributesToArray());
                // dump($referred_participant->promoter);
                $referred_participant->update(['promoter' => $referral_user->user_id]);
            }


            dump($referral_user->name);

            /*
            $participant = Participant::firstWhere('code', $promoter);
            if (!is_null($participant)) {
                $participant->points = $participant->points + 10;
                $promoter = $participant->code;
            }
            */

            /*
            $student_association = StudentAssociation::firstWhere('code', $promoter);
            if (!is_null($student_association)) {
                $student_association->points = $student_association->points + 20;

                $promoter_code = $student_association->code;

                // save
                if (!is_null($promoter_code)) {
                    // $participant->save();
                    $student_association->save();
                }
            }
            */
        }
    }
}
