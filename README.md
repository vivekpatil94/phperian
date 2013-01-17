Experian Web Service Library
============================

This package is a collection of classes designed to assist the generation of XML for a SOAP service request to
Experian's Web Service, and to interpret their response.

License
-------

Written for [Nosco Systems](http://nosco-systems.co.uk), it is licensed under [MIT/X11](http://j.mp/mit-license). The `LICENSE` file can be found in the root directory of the package.

Installation
------------

This package will eventually find its way to [Packagist](https://packagist.org/) when a stable version is released, but
until then it will only be available through its GitHub [repository page][repo] and its practicality of use cannot be
gauranteed.

To install this package in its current form, add this repository to your project's `composer.json`, and add this package
as a requirement:

    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/mynameiszanders/experianwebservice"
        }
    ],
    "require": {
        "nosco/experian": "dev-master"
    }

Example Usage
-------------

```php
<?php

    // Start a new request.
    $request = new \Nosco\Request;

    // Create a new applicant.
    $applicant = $request   ->createApplicant('Zander', 'Baldwin')
                            ->setGenderMale()
                            ->dateOfBirth(1970, 1, 1);

    // Create a new location.
    $location = $request    ->createLocation()
                            ->name('Buckingham Palace')
                            ->postcode('SW1A 1AA');

    // Tie the applicant with the location.
    $residency = $request   ->residency($applicant, $location, $request::LOCATION_CURRENT)
                            ->dateFrom(1970, 1, 1)
                            ->dateTo(2012, 12, 21);

    $request->setCertificate($file_to_certificate);
    $request->setCertificatePassword($certificate_password);
    $request->setPrivateKey($file_to_private_key);

    $response = $request->request();
```

Authors
-------

This package was written by [Zander Baldwin](http://mynameiszanders.github.com), with the help of [Clive Dann](http://clivedann.co.uk).

[repo]: https://github.com/mynameiszanders/experianwebservice