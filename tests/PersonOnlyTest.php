<?php

namespace Endeavors\MaxMD\Registration\Tests;

use Endeavors\MaxMD\Registration\Person\Person;
use Endeavors\MaxMD\Registration\Person\Certified;
use Endeavors\MaxMD\Registration\Person\PersonFactory;

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
    }

    public function testCreatingNewPersonAfterIdRequested()
    {
        $person = Person::create($this->response(123, "LoA3Certified"));

        $this->assertTrue($person->hasId());

        $this->assertEquals(123, $person->id());

        $newPerson = Person::create();

        $this->assertNull($newPerson);
    }

    public function testCreatingNewPersonBeforeIdRequested()
    {
        $person = Person::create($this->response(123, "LoA3Certified"));

        $newPerson = Person::create();

        $this->assertEquals(123, $newPerson->id());
    }

    public function testCreatingPersonVerifiedAndAuthenticated()
    {
        $person = PersonFactory::certified($this->response(22, "VerifiedAndAuthenticated"));

        $this->assertNull($person);

        $newPerson = PersonFactory::certified($this->response(44, "VerifiedAndAuthenticated"));

        $this->assertNull($newPerson);

        $newPerson = PersonFactory::certified($this->response(77, "Registered"));

        $this->assertNull($newPerson);
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
