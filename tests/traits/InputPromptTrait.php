<?php

namespace Endeavors\MaxMD\Registration\Tests\Traits;

trait InputPromptTrait {

    public function promptFirstname()
    {
        return $this->prompt("Firstname");
    }

    public function promptLastname()
    {
        return $this->prompt("Lastname");
    }

    public function promptStreet()
    {
        return $this->prompt("Street Address");
    }

    public function promptCity()
    {
        return $this->prompt("City");
    }

    public function promptState()
    {
        return $this->prompt("State");
    }

    public function promptCountry()
    {
        return $this->prompt("Country");
    }

    public function promptZip()
    {
        return $this->prompt("Zip Code");
    }

    public function promptDob()
    {
        return $this->prompt("DOB");
    }

    public function promptEmail()
    {
        return $this->prompt("Email");
    }

    public function promptMobile()
    {
        return $this->prompt("Mobile");
    }

    public function promptSsn4()
    {
        $ssn = $this->prompt("SSN4");

        if($ssn !== "") {
            return $ssn;
        }

        return '9999';
    }

    public function promptSocial()
    {
        $ssn = $this->prompt("SSN");

        if($ssn !== "") {
            return $ssn;
        }

        return '999999999';
    }

    protected function prompt($message)
    {
        trim(fputs(fopen("php://stdout", "w"), $message . ": "));
        return $this->getUserInput();
    }

    public function getUserInput()
    {
        return trim(fgets(fopen("php://stdin","r")));
    }
}