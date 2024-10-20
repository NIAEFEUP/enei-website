<?php

namespace App\Traits;

use App\Libs\JWTLib;

trait HasReferralLink
{
    abstract private function getPromoterCode(): string;

    public function getReferralLink(): string
    {
        $jwt_signer = new JWTLib('TODO: update this to use signer registered to application container. That one should be instantiated with a key from env');

        $payload = [
            'promoter' => $this->getPromoterCode(),
        ];

        $code = $jwt_signer->generate($payload);

        $link = route('register_with_promoter', ['promoter' => $code]);

        return $link;
    }
}
