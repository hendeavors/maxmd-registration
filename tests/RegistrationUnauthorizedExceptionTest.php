<?php

namespace Endeavors\MaxMD\Proofing\Tests;

use Endeavors\MaxMD\Proofing\IdentityProof;
use Endeavors\MaxMD\Api\Auth\MaxMD;
use Endeavors\MaxMD\Api\Auth\Session;
use Endeavors\MaxMD\Registration\Person\Patient;
use PHPUnit\Framework\TestCase;

class RegistrationUnauthorizedExceptionTest extends TestCase
{
    public function setUp()
    {
        MaxMD::Logout();

        parent::setUp();
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage The credentials supplied are either invalid or your session has timed out.
     */
    public function testExceptionIsThrownWhenBadCredentialsUsed()
    {
        MaxMD::Logout();

        MaxMD::Login("bad", "bad");

        Patient::proof([]);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage The credentials supplied are either invalid or your session has timed out.
     */
    public function testExceptionIsThrownWhenBadCredentialsUsedWithoutLoggingIn()
    {
        Patient::proof([]);
    }
}
