<?php

namespace App\Traits;

use App\Services\HashIdService;

trait HasReferralLink
{
    abstract private function getPromoterCode(): string;

    public function getReferralLink(): string
    {
        $referral_code = (new HashIdService())->encode($this->getPromoterCode());

        return route('register', ['ref' => $referral_code]);
    }
}
