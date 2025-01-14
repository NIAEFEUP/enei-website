<?php

namespace App\Traits;

use App\Services\HashIdService;

trait HasReferralLink
{
    abstract private function getPromoterCode(): string;

    public function getReferralCode(): string
    {
        return (new HashIdService)->encode($this->getPromoterCode());
    }

    public function getReferralLink(): string
    {
        return route('register', ['ref' => $this->getReferralCode()]);
    }
}
