<?php

namespace Endeavors\MaxMD\Registration\Tests;


use Endeavors\MaxMD\Api\Auth\MaxMD;
use Endeavors\MaxMD\Api\Auth\Session;
use Endeavors\MaxMD\Registration\Person\Registration;
use Endeavors\MaxMD\Registration\Person\Patient;

class DynamicVerificationTest extends TestCase
{
    use Traits\InputPromptTrait;

    public function setUp()
    {
        parent::setUp();
        MaxMD::Login(getenv("MAXMD_APIUSERNAME"),getenv("MAXMD_APIPASSWORD"));
    }

    public function testProofing()
    {
        // The message will either be MFAOTPGenerated or LoA3Certified if already verified the one time password
        // we assume the person can be proofed by maxmd, if this test fails then person cannot be verified
        $response = Patient::Proof($this->inputPerson());

        $result = $response->verificationStatus === "LoA3Certified" || $response->verificationStatus === "MFAOTPGenerated";

        $this->assertTrue($result);
    }

    // The test may fail with a status of Registered, unsure as to why
    public function testVerifyingPhone()
    {
        // The message will either be MFAOTPGenerated or LoA3Certified if already verified the one time password
        $response = Patient::Proof($this->inputPerson());

        $person = [
            'otp' => $this->promptPassword(),
            'personMeta' => [
                'firstName' => $response->personMeta->firstName,
                'lastName' => $response->personMeta->lastName,
                'ssn4' => $response->personMeta->ssn4,
                'dob' => $response->personMeta->dob
            ]
        ];

        $response = Patient::VerifyMobile($person);

        $verifiedResult = $response->code === "000" && $response->verificationStatus === "LoA3Certified";

        $alreadyVerifiedResult = $response->code === "AP:5" && $response->verificationStatus === "VerifiedAndAuthenticated";

        $this->assertTrue($alreadyVerifiedResult || $verifiedResult);
    }

    public function testVerifyingPhoneAndProvision()
    {
        // The message will either be MFAOTPGenerated or LoA3Certified if already verified the one time password
        $response = Patient::Proof($this->inputPerson());

        $person = [
            'otp' => $this->promptPassword(),
            'personMeta' => [
                'firstName' => $response->personMeta->firstName,
                'lastName' => $response->personMeta->lastName,
                'ssn4' => $response->personMeta->ssn4,
                'dob' => $response->personMeta->dob
            ]
        ];

        $username = "freddie";
        $password = "smith";

        // The idea is to verify the mobile at the same time as provisioning
        // The callback is only executed if the phone number can be verified
        $response = Patient::VerifyMobile($person, function($provision, $id) use($username, $password) {
            $response = $provision->ProvisionIDProofedPatient(getenv('MAXMD_DOMAIN'), ['idpId' => $id], $username, $password);
        });
    }

    public function testProvision()
    {
        $username = "freddie";
        $password = "smith";
        // The mobile number must be verified first, verificationStatus should be LoA3Certified to execute the callback
        $response = Patient::Provision($this->inputPerson(), function($provision, $id) use($username, $password) {
            $response = $provision->ProvisionIDProofedPatient(getenv('MAXMD_DOMAIN'), ['idpId' => $id], $username, $password);
        });
    }

    protected function inputPerson()
    {
        $input = [
            'ssn' => $this->promptSocial(),
            'email' => $this->promptEmail(),
            'mobilePhone' => $this->promptMobile(),
            'ssn4' => $this->promptSsn4(),
            'street1' => $this->promptStreet(),
            'city' => $this->promptCity(),
            'state' => $this->promptState(),
            'country' => $this->promptCountry(),
            'zip5' => $this->promptZip(),
            'firstName' => $this->promptFirstname(),
            'lastName' => $this->promptLastname(),
            'dob' => $this->promptDob()
        ];

        return $input;
    }

    public function promptPassword()
    {
        return $this->prompt("One Time Password");
    }

    public function getPassword()
    {
        return $this->getUserInput();
    }
}
