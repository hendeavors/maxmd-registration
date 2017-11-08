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

To easily verifiy the credit card:
```php
$person = [
    'personMeta' => [
       'firstName' => 'freddie',
       'lastName' => 'smith',
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

$response = Patient::VerifyCreditCard($person, function($provision, $id) use($username, $password) {
    $response = $provision->ProvisionIDProofedPatient("yourown.direct.domain.here", ['idpId' => $id], $username, $password);
});
```

If the mobile number for the individual has already been verified, you may still acquire the id necessary to provision the person:

```php
$response = Patient::Provision($person, function($provision, $id) use($username, $password) {
    $response = $provision->ProvisionIDProofedPatient("yourown.direct.domain.here", ['idpId' => $id], $username, $password);
});
```

You may use the VerifyAll method to attempt one or both methods of verification. The phone is attempted first:

```php
$response = Patient::VerifyAll($person, function($provision, $id) use($username, $password) {
    $response = $provision->ProvisionIDProofedPatient("yourown.direct.domain.here", ['idpId' => $id], $username, $password);
});
```

Note: The username and password for the person are necessary to create the account with maxmd and receive a direct message account.

# Acquiring A Direct Address

To ensure you have the correct direct address:

```php
$response = Patient::Provision($person, function($provision, $id) use($username, $password) {
    $response = $provision->ProvisionIDProofedPatient("yourown.direct.domain.here", ['idpId' => $id], $username, $password);
    // Call get address by username, a bit misleading as this does not return the direct address
    $provision->GetPatientAddressByUserName("yourown.direct.domain.here", "freddie");
    // get the direct address username@yourown.direct.domain.here
    $directAddress = $provision->DirectAddress();
});
```

To perform manually:

```php
// Proof
// Verify mobile
// Provision
$provision = new \Endeavors\MaxMD\Registration\Person\Registration();
// assume freddie has performed and passed the above steps
$provision->GetPatientAddressByUserName("yourown.direct.domain.here", "freddie");

$directAddress = $provision->DirectAddress();
```

# Provisioning Successful Response

Assuming you receive a successful response from maxmd and use freddie as the username. The response will be in the following format:

```
response: {#176
    "return": {#177
      "code": "000"
      "message": "Provisioned Direct address freddie@healthendeavors.direct.eval.md for patient Sdfsdf Rodriguez. The Direct address will be activated after the Direct certificate is issued."
      "success": true
      "users": {#178
        "address": "1234 Fake street"
        "city": "Fake Town"
        "country": "us"
        "dea": ""
        "email": "fake@email.com"
        "firstName": "Sdfsdf"
        "lastName": "Rodriguez"
        "npi": ""
        "phone": "14803646662"
        "sameAsOrganizationAddress": false
        "state": "ALASKA"
        "zipcode": "85412"
        "comsumeMdnFlag": false
        "endpoint": ""
        "endpointDirectory": "null"
        "endpointPassword": "null"
        "endpointUsername": "null"
        "notifyFlag": true
        "userType": "SMTP"
        "username": "freddie"
        "userState": "activated"
      }
      "paymentConfirmation": {#179
        "amount": 60.0
        "detail": "Your payment for purchasing 1 MaxMD Patient Direct Address for patient Direct address: freddie@healthendeavors.direct.eval.md has been placed."
        "orderId": 54930
        "orderTime": "2017-11-03T11:27:27-05:00"
        "products": {#180
          "amount": 60.0
          "description": "MaxMD Patient Direct Address 8/month 60/year"
          "expireDate": "2018-11-03"
          "name": "MaxMD Patient Direct Address"
          "offerPrice": 60.0
          "quantity": 1
          "salePrice": 60.0
          "startDate": "2017-11-03"
        }
      }
    }
  }
}
```
