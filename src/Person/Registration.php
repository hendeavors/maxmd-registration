<?php

namespace Endeavors\MaxMD\Registration\Person;

use Endeavors\MaxMD\Registration\Contracts\IRegister;
use Endeavors\MaxMD\Support\Client;
use Endeavors\MaxMD\Api\Auth\Session;
use Endeavors\MaxMD\Registration\Traits\PatientAddressTrait;
use Endeavors\MaxMD\Api\Auth\UnauthorizedAccessException;

class Registration implements IRegister
{
    use PatientAddressTrait;

    protected $response;

    protected $myDirectDomain = '';

    public function ProvisionIDProofedPatient($directDomain, $request = array(), $directUsername, $directPassword)
    {
        if(Session::Check()) {
            $registration = [
                'sessionId' => Session::getId(), 
                'DirectDomain' => $directDomain, 
                "patient" => $request, 
                'DirectUsername' => $directUsername,
                'DirectPassword' => $directPassword
            ];

            $this->response = Client::PatientRegistration()->ProvisionIDProofedPatient($registration);

            $this->setDirectDomain($directDomain);

            return $this;
        }

        throw new UnauthorizedAccessException("The credentials supplied are either invalid or your session has timed out.");
    }

    public function GetPatientAddressByMeta($directDomain, $request = array())
    {
        if( Session::check() ) {
            // do stuff
            $this->setDirectDomain($directDomain);

            return $this;
        }

        throw new UnauthorizedAccessException("The credentials supplied are either invalid or your session has timed out.");
    }
    
    /**
     * @param string directDomain
     * @param string directUsername
     * @todo add validation
     */
    public function GetPatientAddressByUserName($directDomain, $directUsername)
    {
        if( Session::check() ) {
            // do stuff
            $registration = [
                'sessionId' => Session::getId(), 
                'DirectDomain' => $directDomain,
                'DirectUsername' => $directUsername
            ];

            $this->response = Client::PatientRegistration()->GetPatientAddressByUsername($registration);

            $this->setDirectDomain($directDomain);

            return $this;
        }

        throw new UnauthorizedAccessException("The credentials supplied are either invalid or your session has timed out.");
    }

    public function ToObject()
    {
        return $this->Raw()->return;
    }

    public function Raw()
    {
        return $this->response;
    }

    protected function setDirectDomain($directDomain)
    {
        $this->myDirectDomain = $directDomain;

        return $this;
    }
}