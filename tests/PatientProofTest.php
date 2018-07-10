<?php

namespace Endeavors\MaxMD\Registration\Tests;

use Endeavors\MaxMD\Api\Auth\MaxMD;
use Endeavors\MaxMD\Api\Auth\Session;
use Endeavors\MaxMD\Registration\Person\Registration;
use Endeavors\MaxMD\Registration\Person\Patient;
use Endeavors\MaxMD\Registration\Person\Person;

/**
 * What to do if we get No person is loaded. Please register a person or find a registered person first?
 */
class PatientProofTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        MaxMD::Login(env("MAXMD_APIUSERNAME"),env("MAXMD_APIPASSWORD"));

        parent::setUp();
    }

    public function testCheckingPerson()
    {
        $response = Patient::Proof($this->person());
        // im valid at this point
        $this->assertTrue($response->success);
    }

    public function testPersonHasId()
    {
        // the test above creates the person
        $person = Person::create();

        $this->assertTrue($person->hasId());
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
}
