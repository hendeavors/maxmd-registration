<?php

namespace Endeavors\MaxMD\Registration\Person;

use Endeavors\MaxMD\Registration\Contracts\IRegister;
use Endeavors\MaxMD\Support\Client;
use Endeavors\MaxMD\Api\Auth\Session;

class Registration implements IRegister
{
    protected $response;

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
        }

        return $this;
    }

    public function GetPatientAddressByMeta($directDomain, $request = array())
    {
        if( Session::check() ) {
            // do stuff
        }
    }

    public function GetPatientAddressByUserName($directDomain, $directUsername)
    {
        if( Session::check() ) {
            // do stuff
        }
    }

    public function Raw()
    {
        return $this->response;
    }
}