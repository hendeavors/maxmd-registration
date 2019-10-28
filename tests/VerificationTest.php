<?php

namespace Endeavors\MaxMD\Registration\Tests;

use Endeavors\MaxMD\Api\Auth\MaxMD;
use Endeavors\MaxMD\Api\Auth\Session;
use Endeavors\MaxMD\Registration\Person\Registration;
use Endeavors\MaxMD\Registration\Person\Patient;

class VerificationTest extends TestCase
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
        // We assume the person can be proofed by maxmd, if this test fails then person cannot be verified
        $response = Patient::Proof($this->person());

        $result = $response->verificationStatus === "LoA3Certified" || $response->verificationStatus === "MFAOTPGenerated";

        $this->assertTrue($result);
    }

    /**
     * We'll get code AP:2 back from maxmd
     * @todo
     */
    public function testCantFindPersonWhenVerifyingPhone()
    {

    }

    // The test may fail with a status of Registered, unsure as to why
    public function testVerifyingPhone()
    {
        // the message will either be MFAOTPGenerated or LoA3Certified if already verified the one time password
        $response = Patient::Proof($this->person());

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

    public function testVerifyingCreditCard()
    {
        // the message will either be MFAOTPGenerated or LoA3Certified if already verified the one time password
        $response = Patient::Proof($this->person());

        $person = [
            'personMeta' => [
                'firstName' => $response->personMeta->firstName,
                'lastName' => $response->personMeta->lastName,
                'ssn4' => $response->personMeta->ssn4,
                'dob' => $response->personMeta->dob
            ],
            'creditCard' => [
                'cardNumber' => '4111111111111111',
                'cvv' => '382',
                'expireYear' => '2019',
                'expireMonth' => '09'
            ]
        ];

        $response = Patient::VerifyCreditCard($person, function($provision, $id) {
            // if we get here we'll have an id
            $this->assertNotNull($id);
        });
    }

    public function testVerifyingPhoneAndProvision()
    {
        // the message will either be MFAOTPGenerated or LoA3Certified if already verified the one time password
        $response = Patient::Proof($this->person());

        $person = [
            'otp' => $this->promptPassword(),
            'personMeta' => [
                'firstName' => $response->personMeta->firstName,
                'lastName' => $response->personMeta->lastName,
                'ssn4' => $response->personMeta->ssn4,
                'dob' => $response->personMeta->dob
            ]
        ];

        $username = "bobbie";
        $password = "smith";

        // The idea is to verify the mobile at the same time as provisioning
        // The callback is only executed if the phone number can be verified
        $response = Patient::VerifyMobile($person, function($provision, $id) use($username, $password) {
            $this->assertNotNull($id);
            $response = $provision->ProvisionIDProofedPatient(getenv('MAXMD_DOMAIN'), ['idpId' => $id], $username, $password);
        });
    }

    public function testProvision()
    {
        $username = "freddie";
        $password = "smith";
        // The mobile number must be verified first, verificationStatus should be LoA3Certified to execute the callback
        $response = Patient::Provision($this->person(), function($provision, $id) use($username, $password) {
            $this->assertNotNull($id);
            $response = $provision->ProvisionIDProofedPatient(getenv('MAXMD_DOMAIN'), ['idpId' => $id], $username, $password);
        });
    }

    protected function person()
    {
        return [
            'ssn' => '999999999',
            'mobilePhone' => "4803646662",
            'email' => 'fake@email.com',
            'street1' => '1234 Fake street',
            'city' => 'Fake Town',
            'state' => 'AK',
            'country' => 'US',
            'zip5' => '85412',
            'firstName' => 'Adam',
            'lastName' => 'Rodriguez',
            'ssn4' => '9999',
            'dob' => '1985-10-03'
        ];
    }

    public function promptPassword()
    {
        return $this->prompt("One Time Password");
    }
}
