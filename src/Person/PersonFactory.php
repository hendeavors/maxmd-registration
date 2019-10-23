<?php

namespace Endeavors\MaxMD\Registration\Person;

use Endeavors\Contracts\Interoperability;
use Endeavors\Interoperability\Services\MaxMD;

class PersonFactory
{
    public static function certified($proofResponse = null)
    {
        return Certified::create(static::person($proofResponse));
    }

    public static function person($proofResponse = null)
    {
        return Person::create($proofResponse);
    }
}
