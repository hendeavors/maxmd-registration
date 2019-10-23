<?php

namespace Endeavors\MaxMD\Registration\Tests;

use Endeavors\MaxMD\Api\Auth\MaxMD;
use Endeavors\MaxMD\Registration\Person\Registration;
use PHPUnit\Framework\TestCase;

class AddressByUsernameTest extends TestCase
{
    public function setUp()
    {
        MaxMD::Login(getenv("MAXMD_APIUSERNAME"),getenv("MAXMD_APIPASSWORD"));

        parent::setUp();
    }

    /**
     *
     */
    public function testGettingStatusOfProvisionedUser()
    {
        $provision = new Registration();
        // freddie was provisioned in an earlier test
        $provision->GetPatientAddressByUserName(getenv('MAXMD_DOMAIN'), "freddie");

        $this->assertTrue($provision->Status() === "activated");

        $this->assertEquals($provision->DirectAddress(), "freddie@" . getenv('MAXMD_DOMAIN'));
    }

    /**
     *
     */
    public function testGettingStatusOfUnprovisionedUser()
    {
        $provision = new Registration();
        // freddies was not provisioned in an earlier test
        $provision->GetPatientAddressByUserName(getenv('MAXMD_DOMAIN'), "freddies");

        $this->assertFalse($provision->Status());

        $this->assertNull($provision->DirectAddress());
    }

    /**
     *
     */
    public function testGettingUsernameOfProvisionedUser()
    {
        $provision = new Registration();
        // freddie was provisioned in an earlier test
        $provision->GetPatientAddressByUserName(getenv('MAXMD_DOMAIN'), "freddie");

        $this->assertEquals($provision->Username(), "freddie");

        $this->assertEquals($provision->DirectAddress(), "freddie@" . getenv('MAXMD_DOMAIN'));
    }

    /**
     *
     */
    public function testGettingUsernameOfUnprovisionedUser()
    {
        $provision = new Registration();
        // freddies was not provisioned in an earlier test
        $provision->GetPatientAddressByUserName(getenv('MAXMD_DOMAIN'), "freddies");

        $this->assertNull($provision->Username());

        $this->assertNull($provision->DirectAddress());
    }

    public function testInvalidDirectAddress()
    {
        $provision = new Registration();

        $provision->GetPatientAddressByUserName("some.invalid.direct.md", "freddie");

        $this->assertNull($provision->Username());

        $this->assertNull($provision->DirectAddress());
    }
}
