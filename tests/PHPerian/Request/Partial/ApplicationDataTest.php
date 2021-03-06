<?php

    namespace PHPerian\Request\Partial;

    use \PHPUnit_Framework_TestCase as TestCase;
    use \PHPerian\Exception as Exception;

    /**
     * PHPerian: PHP library for Experian's Web Services
     *
     * @package     PHPerian
     * @category    Library
     * @author      Zander Baldwin <mynameiszanders@gmail.com>
     * @license     MIT/X11 <http://j.mp/mit-license>
     * @link        https://github.com/mynameiszanders/phperian/blob/develop/tests/PHPerian/Request/Partial/ApplicationDataTest.php
     */
    class ApplicationDataTest extends TestCase
    {

        private $request = null;

        public function createInstance()
        {
            $request = new \PHPerian\Request;
            $this->assertTrue(
                is_object($request),
                'Ensure that a new Request object can be created.'
            );
            $applicant = $request->createApplicant('Test', 'Case');
            $this->assertTrue(
                is_object($applicant),
                'Ensure that a new Applicant object can be created.'
            );
            $appdata = $request->createApplicationData($applicant);
            $this->assertTrue(
                is_object($appdata),
                'Ensure that a new Application object can be created through the Request object\'s magic method.'
            );
            $this->request = $request;
            return $appdata;
        }

        public function testApplicationDataCreation()
        {
            $request = new \PHPerian\Request;
            $this->assertTrue(
                is_object($request),
                'Ensure that a new Request object can be created.'
            );

            $applicant = $request->createApplicant('Test', 'Case');
            $this->assertTrue(
                is_object($applicant),
                'Ensure that a new Applicant object can be created.'
            );

            $appdata = new \PHPerian\Request\Partial\ApplicationData($applicant);
            $this->assertTrue(
                is_object($appdata),
                'Ensure that a new instance of the Application class can be created successfully.'
            );

            $appdata = $request->createApplicationData($applicant);
            $this->assertTrue(
                is_object($appdata),
                'Ensure that a new Application object can be created through the Request object\'s magic method.'
            );
        }

        public function testMaritalStatus()
        {
            $appdata = $this->createInstance();
            $this->assertTrue(
                $appdata->maritalStatus() === null,
                'Ensure that the return value is none when a marital status has not yet been set.'
            );
            $this->assertTrue(
                is_object($appdata->maritalStatus('S')),
                'Ensure that an object is returned when a valid value is set.'
            );
            $this->assertTrue(
                $appdata->maritalStatus() === 'S',
                'Ensure that the return value is the same as the one we set.'
            );
            $this->request->silent();
            $this->assertTrue(
                is_object($appdata->maritalStatus('A')),
                'Ensure that an object is returned when an invalid value is set in silent mode().'
            );
            $this->assertTrue(
                $appdata->maritalStatus() === 'S',
                'Ensure that the invalid value does not override our previously set value.'
            );
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->maritalStatus('A');
        }

        public function testHomeTelephone()
        {
            $appdata = $this->createInstance();
            $this->assertTrue(
                $appdata->homeTelephone() === null,
                'Ensure that the return value is null when a home telephone number has not yet been set.'
            );
            $this->assertTrue(
                is_object($appdata->workTelephone('N')),
                'Ensure that an object is returned when a single string parameter is passed to signify unavailability.'
            );
            $this->assertTrue(
                $appdata->workTelephone() === 'N',
                'Ensure that the return value is the same as the one we set.'
            );
            $this->assertTrue(
                is_object($appdata->homeTelephone('01452', '123456')),
                'Ensure that an object is returned when a valid value is set.'
            );
            $this->assertTrue(
                $appdata->homeTelephone() === '01452 123456',
                'Ensure that a formatted string from our passed values is returned.'
            );
            $this->request->silent();
            $this->assertTrue(
                is_object($appdata->homeTelephone(123, 456)),
                'Ensure that an object is returned when non-strings are passed in silent mode.'
            );
            $this->assertTrue(
                $appdata->homeTelephone() === '01452 123456',
                'Ensure that our previous values were not overwritten when in silent mode.'
            );
            $this->assertTrue(
                is_object($appdata->homeTelephone('01242')),
                'Ensure that an object is returned when only one parameter is passed in silent mode.'
            );
            $this->assertTrue(
                $appdata->homeTelephone() === '01452 123456',
                'Ensure that our previous values were not overwritten when only one parameter was passed.'
            );
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->homeTelephone('123a');
        }

        public function testMobileTelephone()
        {
            $appdata = $this->createInstance();
            $this->assertTrue(
                $appdata->mobileTelephoneNumber() === null,
                'Ensure that the return value is null when a home telephone area has not yet been set.'
            );
            $this->assertTrue(
                is_object($appdata->mobileTelephoneNumber('01452')),
                'Ensure that an object is returned when a valid value is set.'
            );
            $this->assertTrue(
                $appdata->mobileTelephoneNumber() === '01452',
                'Ensure that the return value is the same as the one we set.'
            );
            $this->request->silent();
            $this->assertTrue(
                is_object($appdata->mobileTelephoneNumber('12 34')),
                'Ensure that an object is returned when an invalid value is set in silent mode.'
            );
            $this->assertTrue(
                $appdata->mobileTelephoneNumber() === '01452',
                'Ensure that our previous value was not overwritten with the invalid value.'
            );
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->mobileTelephoneNumber('12 34');
        }

        public function testDependants()
        {
            $appdata = $this->createInstance();
            $this->assertTrue(
                $appdata->dependants() === null,
                'Ensure that the return value is null when nothing has been set yet.'
            );
            $this->assertTrue(
                is_object($appdata->dependants(3)),
                'Ensure that an object is returned when a valid value is set.'
            );
            $this->assertTrue(
                $appdata->dependants() === 3,
                'Ensure that the return value is the same as the one we set.'
            );
            $appdata->dependants(14);
            $this->assertTrue(
                $appdata->dependants() === 7,
                'Ensure that a value above seven is returned as seven, since we set it as an integer.'
            );
            $appdata->dependants('Z');
            $this->assertTrue(
                $appdata->dependants() === 8,
                'Ensure that the return value is an integer 8, when "Z" (not given) is set.'
            );
            $appdata->dependants('Q');
            $this->assertTrue(
                $appdata->dependants() === 9,
                'Ensure that the return value is an integer 9, when "Q" (not asked) is set.'
            );
            $this->request->silent();
            $this->assertTrue(
                is_object($appdata->dependants('asd')),
                'Ensure that an object is returned when an invalid value is set in silent mode.'
            );
            $this->assertTrue(
                $appdata->dependants() === 9,
                'Ensure that our previous value was not overwritten when an invalid value was set in silent mode.'
            );
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->dependants('asd');
        }

        public function testResidentialStatus()
        {
            $appdata = $this->createInstance();
            $this->assertTrue(
                $appdata->residentialStatus() === null,
                'Ensure that the return value is none when a marital status has not yet been set.'
            );
            $this->assertTrue(
                is_object($appdata->residentialStatus('O')),
                'Ensure that an object is returned when a valid value is set.'
            );
            $this->assertTrue(
                $appdata->residentialStatus() === 'O',
                'Ensure that the return value is the same as the one we set.'
            );
            $this->request->silent();
            $this->assertTrue(
                is_object($appdata->residentialStatus('A')),
                'Ensure that an object is returned when an invalid value is set in silent mode().'
            );
            $this->assertTrue(
                $appdata->residentialStatus() === 'O',
                'Ensure that the invalid value does not override our previously set value.'
            );
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->residentialStatus('A');
        }

        public function testEmailAddress()
        {
            $appdata = $this->createInstance();
            $this->assertTrue(
                $appdata->emailAddress() === null
            );
            $this->assertTrue(is_object($appdata->emailAddress('test@example.com')));
            $this->assertTrue($appdata->emailAddress() === 'test@example.com');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->emailAddress('notanemailaddress')));
            $this->assertTrue($appdata->emailAddress() === 'test@example.com');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->emailAddress('notanemailaddress');
        }

        public function testNationalInsuranceNumber()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->nationalInsuranceNumber() === null);
            $this->assertTrue(is_object($appdata->nationalInsuranceNumber('AB123456C')));
            $this->assertTrue($appdata->nationalInsuranceNumber() === 'AB123456C');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->nationalInsuranceNumber('this is completely wrong')));
            $this->assertTrue($appdata->nationalInsuranceNumber() === 'AB123456C');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->nationalInsuranceNumber('this is completely wrong');
        }

        public function testPassportNumber()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->passportNumber() === null);
            $this->assertTrue(is_object($appdata->passportNumber('AB123456C')));
            $this->assertTrue($appdata->passportNumber() === 'AB123456C');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->passportNumber('this is completely wrong')));
            $this->assertTrue($appdata->passportNumber() === 'AB123456C');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->passportNumber('this is completely wrong');
        }

        public function testCountryOfBirth()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->countryOfBirth() === null);
            $this->assertTrue(is_object($appdata->countryOfBirth('E')));
            $this->assertTrue($appdata->countryOfBirth() === 'E');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->countryOfBirth('A')));
            $this->assertTrue($appdata->countryOfBirth() === 'E');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->countryOfBirth('A');
        }

        public function testTimeWithBank()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->timeWithBank() === null);
            $this->assertTrue(is_object($appdata->timeWithBank(10, 10)));
            $this->assertTrue($appdata->timeWithBank() === '10y 10m');
            $this->assertTrue(is_object($appdata->timeWithBank(10)));
            $this->assertTrue($appdata->timeWithBank() === '10y');
            $this->assertTrue(is_object($appdata->timeWithBank(0, 10)));
            $this->assertTrue($appdata->timeWithBank() === '10m');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->timeWithBank(10, 'asd')));
            $this->assertTrue($appdata->timeWithBank() === '10m');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->timeWithBank(10, 'asd');
        }

        public function testBankSortCode()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->bankSortCode() === null);
            $this->assertTrue(is_object($appdata->bankSortCode('AB123456C')));
            $this->assertTrue($appdata->bankSortCode() === 'AB123456C');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->bankSortCode('this is completely wrong')));
            $this->assertTrue($appdata->bankSortCode() === 'AB123456C');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->bankSortCode('this is completely wrong');
        }

        public function testBankAccountNumber()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->bankAccountNumber() === null);
            $this->assertTrue(is_object($appdata->bankAccountNumber('AB123456C')));
            $this->assertTrue($appdata->bankAccountNumber() === 'AB123456C');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->bankAccountNumber('this is completely wrong')));
            $this->assertTrue($appdata->bankAccountNumber() === 'AB123456C');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->bankAccountNumber('this is completely wrong');
        }

        public function testCurrentAccountHeld()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->currentAccountHeld() === null);
            $this->assertTrue(is_object($appdata->currentAccountHeld(true)));
            $this->assertTrue($appdata->currentAccountHeld() === true);
            $this->assertTrue(is_object($appdata->currentAccountHeld(false)));
            $this->assertTrue($appdata->currentAccountHeld() === false);
            $this->assertTrue(is_object($appdata->currentAccountHeld('Q')));
            $this->assertTrue($appdata->currentAccountHeld() === 'Q');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->currentAccountHeld('A')));
            $this->assertTrue($appdata->currentAccountHeld() === 'Q');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->currentAccountHeld('A');
        }

        public function testCheckCardHeld()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->checkCardHeld() === null);
            $this->assertTrue(is_object($appdata->checkCardHeld(true)));
            $this->assertTrue($appdata->checkCardHeld() === true);
            $this->assertTrue(is_object($appdata->checkCardHeld(false)));
            $this->assertTrue($appdata->checkCardHeld() === false);
            $this->assertTrue(is_object($appdata->checkCardHeld('Q')));
            $this->assertTrue($appdata->checkCardHeld() === 'Q');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->checkCardHeld('A')));
            $this->assertTrue($appdata->checkCardHeld() === 'Q');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->checkCardHeld('A');
        }

        public function testGrossAnnualIncome()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->grossAnnualIncome() == null);
            $this->assertTrue(is_object($appdata->grossAnnualIncome('123')));
            $this->assertTrue($appdata->grossAnnualIncome() === 123);
            $this->assertTrue(is_object($appdata->grossAnnualIncome(12345)));
            $this->assertTrue($appdata->grossAnnualIncome() === 12345);
            $this->request->silent();
            $this->assertTrue(is_object($appdata->grossAnnualIncome('S4321')));
            $this->assertTrue($appdata->grossAnnualIncome() === 12345);
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->grossAnnualIncome('S4321');
        }

        public function testWorkTelephone()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->workTelephone() === null);
            $this->assertTrue(is_object($appdata->workTelephone('01452', '123456')));
            $this->assertTrue($appdata->workTelephone() === '01452 123456');
            $this->assertTrue(is_object($appdata->workTelephone('X')));
            $this->assertTrue($appdata->workTelephone() === 'X');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->workTelephone('01242')));
            $this->assertTrue($appdata->workTelephone() === 'X');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->workTelephone('01242');
        }

        public function testTimeWithEmployer()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->timeWithEmployer() === null);
            $this->assertTrue(is_object($appdata->timeWithEmployer(10, 3)));
            $this->assertTrue($appdata->timeWithEmployer() === '10y 3m');
            $this->assertTrue(is_object($appdata->timeWithEmployer(10)));
            $this->assertTrue($appdata->timeWithEmployer() === '10y');
            $this->assertTrue(is_object($appdata->timeWithEmployer(0)));
            $this->assertTrue($appdata->timeWithEmployer() === '0m');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->timeWithEmployer('11')));
            $this->assertTrue($appdata->timeWithEmployer() === '0m');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->timeWithEmployer('11');
        }

        public function testEmployerName()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->employerName() === null);
            $this->assertTrue(is_object($appdata->employerName('PHPerian')));
            $this->assertTrue($appdata->employerName() === 'PHPerian');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->employerName(123)));
            $this->assertTrue($appdata->employerName() === 'PHPerian');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->employerName(123);
        }

        public function testOccupationStatus()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->occupationStatus() === null);
            $this->assertTrue(is_object($appdata->occupationStatus('T')));
            $this->assertTrue($appdata->occupationStatus() === 'T');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->occupationStatus('A')));
            $this->assertTrue($appdata->occupationStatus() === 'T');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->occupationStatus('A');
        }

        public function testEmploymentStatus()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->employmentStatus() === null);
            $this->assertTrue(is_object($appdata->employmentStatus('E')));
            $this->assertTrue($appdata->employmentStatus() === 'E');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->employmentStatus('A')));
            $this->assertTrue($appdata->employmentStatus() === 'E');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->employmentStatus('A');
        }

        public function testDrivingLicenseNumber()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->drivingLicenseNumber() === null);
            $this->assertTrue(is_object($appdata->drivingLicenseNumber('ABC123')));
            $this->assertTrue($appdata->drivingLicenseNumber() === 'ABC123');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->drivingLicenseNumber('DEF4$6')));
            $this->assertTrue($appdata->drivingLicenseNumber() === 'ABC123');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->drivingLicenseNumber('DEF4$6');
        }

        public function testVehicleRegistration()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->vehicleRegistration() === null);
            $this->assertTrue(is_object($appdata->vehicleRegistration('ABC123')));
            $this->assertTrue($appdata->vehicleRegistration() === 'ABC123');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->vehicleRegistration('DEF4$6')));
            $this->assertTrue($appdata->vehicleRegistration() === 'ABC123');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->vehicleRegistration('DEF4$6');
        }

        public function testPlaceOfBirth()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->placeOfBirth() === null);
            $this->assertTrue(is_object($appdata->placeOfBirth('Hampton Lucy')));
            $this->assertTrue($appdata->placeOfBirth() === 'Hampton Lucy');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->placeOfBirth('H4M70N$ LUCY!')));
            $this->assertTrue($appdata->placeOfBirth() === 'Hampton Lucy');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->placeOfBirth('H4M70N$ LUCY!');
        }

        public function testMothersMaidenName()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->mothersMaidenName() === null);
            $this->assertTrue(is_object($appdata->mothersMaidenName('Smith-Holt')));
            $this->assertTrue($appdata->mothersMaidenName() === 'Smith-Holt');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->mothersMaidenName('$mith-Holt!')));
            $this->assertTrue($appdata->mothersMaidenName() === 'Smith-Holt');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->mothersMaidenName('$mith-Holt!');
        }

        public function testBirthSurname()
        {
            $appdata = $this->createInstance();
            $this->assertTrue($appdata->mothersMaidenName() === null);
            $this->assertTrue(is_object($appdata->mothersMaidenName('Smith-Holt')));
            $this->assertTrue($appdata->mothersMaidenName() === 'Smith-Holt');
            $this->request->silent();
            $this->assertTrue(is_object($appdata->mothersMaidenName('$mith-Holt!')));
            $this->assertTrue($appdata->mothersMaidenName() === 'Smith-Holt');
            $this->request->verbose();
            $this->setExpectedException('\\PHPerian\\Exception');
            $appdata->mothersMaidenName('$mith-Holt!');
        }

    }