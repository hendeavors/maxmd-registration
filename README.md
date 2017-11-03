# maxmd-registration
This library is an adaptation of the api provided by maxmd. The idea is to provide an elegant, simple, and flexible api to easily integrate with maxmd.

To easily proof a patient:

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
