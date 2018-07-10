<?php

namespace Endeavors\MaxMD\Registration\Person;

use Endeavors\Contracts\Interoperability;
use Endeavors\Interoperability\Services\MaxMD;

class Certified implements Interoperability\IPerson
{
    private static $instance = null;

    private $person = null;

    private function __construct(Interoperability\IPerson $person)
    {
        $this->person = $person;
    }

    final private static function instance()
    {
        return static::$instance;
    }

    final public static function create(Interoperability\IPerson $person = null)
    {
        // the individual must be certified
        if( null !== $person && $person->status() === "LoA3Certified" ) {
            static::$instance = new static($person);
        }

        return static::instance();
    }

    public function service()
    {
        return $this->person->service();
    }

    public function id()
    {
        return $this->person->id();
    }

    public function hasId()
    {
        return $this->person->hasId();
    }

    public function status()
    {
        return $this->person->status();
    }

    public function clear()
    {
        static::$instance = null;
    }
}
