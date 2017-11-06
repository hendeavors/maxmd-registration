<?php

namespace Endeavors\MaxMD\Registration\Traits;

trait PatientAddressTrait {

    /**
     * The person can have more than 1 address, unsure of the behavior
     * @return string|bool
     */
    public function Status()
    {
        if( $this->Addresses() ) {
            return $this->Addresses()->userState;
        }

        return false;
    }
    
    /**
     * The person can have more than 1 address, unsure of the behavior
     * @return string|bool
     */
    public function Username()
    {
        if( $this->Addresses() ) {
            return $this->Addresses()->username;
        }

        return null;
    }

    protected function Addresses()
    {
        if( property_exists($this->ToObject(), 'addresses') ) {
            return $this->ToObject()->addresses;
        }

        return false;
    }

    public function DirectDomain()
    {
        return $this->myDirectDomain;
    }
    /**
     * We'll perform some validation deciding whether or not to return a direct address
     */
    public function DirectAddress()
    {
        if( null !== $this->Username() ) {
            return $this->Username() . '@' . $this->DirectDomain();
        }

        return null;
    }
}