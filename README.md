# maxmd-registration
This library is an adaptation of the api provided by maxmd. The idea is to provide an elegant, simple, and flexible api to easily integrate with maxmd.

To easily proof a person:

```php
use Endeavors\MaxMD\Registration\Person\Patient;
...
// To easily proof a patient
$person = [
    'ssn' => '999999999',
    'mobilePhone' => "4805555555",
    'email' => 'fake@email.com',
    'street1' => '1234 Fake street',
    'city' => 'Fake Town',
    'state' => 'AK',
    'country' => 'US',
    'zip5' => '85412',
    'firstName' => 'Bob',
    'lastName' => 'Smith',
    'ssn4' => '9999',
    'dob' => '1985-10-03'
];

$response = Patient::Proof($person);
```
This will automatically send a one-time password to the mobile number provided


If you wish, you may also verify the mobile number at the same time as provisioning a direct account for the person:

```php
$response = Patient::VerifyMobile($person, function($provision, $id) use($username, $password) {
    $response = $provision->ProvisionIDProofedPatient("yourown.direct.domain.here", ['idpId' => $id], $username, $password);
});
```

If the mobile number for the individual has already been verified, you may still acquire the id necessary to provision the person:

```php
$response = Patient::Provision($person, function($provision, $id) use($username, $password) {
    $response = $provision->ProvisionIDProofedPatient("yourown.direct.domain.here", ['idpId' => $id], $username, $password);
});
```

Note: The username and password for the person are necessary to create the accound with maxmd and receive a direct message account.
