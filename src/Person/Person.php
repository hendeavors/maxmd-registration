<?php

namespace Endeavors\MaxMD\Registration\Person;

use Endeavors\Contracts\Interoperability;
use Endeavors\Interoperability\Services\MaxMD;

class Person implements Interoperability\IPerson
{
    private static $instance = null;

    private $proofResponse = null;

    private function __construct($proofResponse)
    {
        $this->proofResponse = $proofResponse;
    }

    final private static function instance()
    {
        return static::$instance;
    }

    final public static function create($proofResponse = null)
    {
        // the individual must be certified
        if( null !== $proofResponse && ($proofResponse->verificationStatus === "LoA3Certified"
        || $proofResponse->verificationStatus === "VerifiedAndAuthenticated"
        || $proofResponse->verificationStatus === "VerifiedAndAuthenticatedAndMobileVerified") ) {
            static::$instance = new static($proofResponse);
        }

        return static::instance();
    }

    public function service()
    {
        return new MaxMD();
    }

    public function id()
    {
        // if we request the id, a new person
        // will need to be created with a proof response
        $this->clear();

        if( null !== $this->proofResponse->personMeta ) {
            return (int)$this->proofResponse->personMeta->id;
        }

        return 0;
    }

    public function hasId()
    {
        return $this->id() > 0;
    }

    public function status()
    {
        if( null !== $this->proofResponse ) {
            return $this->proofResponse->verificationStatus;
        }
    }

    public function clear()
    {
        static::$instance = null;
    }
}
