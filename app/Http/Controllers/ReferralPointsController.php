<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\User;
use App\Services\HashIdService;

class ReferralPointsController extends Controller
{
    static $StudentAssociationPoints = 20;
    static $ParticipantPoints = 30;

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
                $referred_participant->update(['promoter' => $referral_user->user_id]);
                $referred_participant = $referred_participant->refresh();
                // add points
                $referral_user->points += ReferralPointsController::$StudentAssociationPoints;
            } else if ($referral_user->isParticipant()) {
                // add points
                $referral_user->points += ReferralPointsController::$ParticipantPoints;
                $referral_association = $referral_user->promoter;
                if (! is_null($referral_association)) {
                    $referral_association += ReferralPointsController::$StudentAssociationPoints;
                }
            }
        }
    }
}
