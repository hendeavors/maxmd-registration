<?php

namespace Endeavors\MaxMD\Registration\Tests;

use Endeavors\MaxMD\Registration\Person\Person;

/**
 * What to do if we get No person is loaded. Please register a person or find a registered person first?
 */
class PersonOnlyTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testCreatingAPerson()
    {
        $person = Person::create();

        $this->assertNull($person);
    }

    public function testCreatingPersonWithProperIdAndStatus()
    {
        $person = Person::create($this->response(1, "LoA3Certified"));

        $this->assertTrue($person->hasId());

        $person->clear();
    }

    public function testCreatingPersonWithProperIdAndWrongStatus()
    {
        $person = Person::create($this->response(1, "Registered"));

        $this->assertNull($person);
    }

    public function testCreatingPersonWithoutProperIdAndStatus()
    {
        $person = Person::create($this->response(0, "LoA3Certified"));

        $this->assertFalse($person->hasId());

        $person->clear();
    }

    public function testCreatingPersonVerifiedAndAuthenticated()
    {
        $person = Person::create($this->response(22, "VerifiedAndAuthenticated"));

        $this->assertTrue($person->hasId());

        $newPerson = Person::create($this->response(44, "VerifiedAndAuthenticated"));

        $this->assertTrue($newPerson->hasId());

        $this->assertEquals(22, $newPerson->id());
    }

    public function testPersonServiceIsMaxMD()
    {
        $person = Person::create($this->response(1, "LoA3Certified"));

        $this->assertEquals($person->service()->name(), "MaxMD");
    }

    protected function response($id = 0, $verificationStatus = "LoA3Certified")
    {
        return (object)[
            "personMeta" => (object)[
                "id" => $id
            ],
            "verificationStatus" => $verificationStatus
        ];
    }
}
