<?php

namespace App\Services;

use Hashids\Hashids;

class HashIdService
{

    public function __construct(
        public $hashIds = new Hashids('', 8),
    ) {}

    public function encode($id)
    {
        return $this->hashIds->encode($id);
    }

    public function decode($hashId)
    {
        if (is_int($hashId))
            return $hashId;
        return $this->hashIds->decode($hashId)[0];
    }
}
