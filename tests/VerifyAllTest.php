<?php

namespace Endeavors\MaxMD\Registration\Tests;

use Endeavors\MaxMD\Api\Auth\MaxMD;
use Endeavors\MaxMD\Api\Auth\Session;
use Endeavors\MaxMD\Registration\Person\Registration;
use Endeavors\MaxMD\Registration\Person\Patient;

/**
 * What to do if we get No person is loaded. Please register a person or find a registered person first?
 */
class VerifyAllTest extends \Orchestra\Testbench\TestCase
{
    use Traits\InputPromptTrait;

    public function setUp()
    {
        MaxMD::Login(env("MAXMD_APIUSERNAME"),env("MAXMD_APIPASSWORD"));

        parent::setUp();
    }

    public function testVerifyingCreditCardOrPhone()
    {
        $person = [
            'otp' => 000111,
            'personMeta' => [
                'firstName' => 'Adam',
                'lastName' => 'Rodriguez',
                'ssn4' => 9999,
                'dob' => '1985-05-05'
            ],
            'creditCard' => [
                'cardNumber' => '4111111111111111',
                'cvv' => '382',
                'expireYear' => '2019',
                'expireMonth' => '09'
            ]
        ];

        $response = Patient::VerifyAll($person, function($provision, $id) {
            $this->assertNotNull($id);
        });
    }

    public function promptPassword()
    {
        return $this->prompt("One Time Password");
    }
}