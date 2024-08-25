<?php

namespace App\Traits;

trait HasReferralLink
{
    public function get_referral_link(): string
    {
        // TODO: Add a proper referral code
        $code = 'TODO'; // $this->promoter;
        // FIXME: This code is currently using the home page
        // for referrals, where should it redirect to?
        $link = route('home', ['ref' => $code]);

        return 'TODO: implement this';
    }
}
