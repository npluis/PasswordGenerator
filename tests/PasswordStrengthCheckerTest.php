<?php
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 7-4-17
 * Time: 23:10
 */

namespace Npluis\PasswordGenerator;

class PasswordStrengthCheckerTest extends \PHPUnit_Framework_TestCase
{
    public function testChecksNumbers()
    {
        $pwGenClass = new PasswordStrengthChecker();

        $pwGen = new \ReflectionClass(get_class($pwGenClass));
        $method = $pwGen->getMethod('checkPassword');
        $method->setAccessible(true);
        $pwGenClass->setMinLength(1);
        $pwGenClass->setMinUniqueChars(1);

        $pwGenClass->setNeedNumbers(3);
        $check = $method->invoke($pwGenClass, 'abc13');
        $this->assertEquals($pwGenClass::NUMBERS, $check);

        $check = $method->invoke($pwGenClass, 'a4bc13');
        $this->assertEquals($pwGenClass::PASSWORD_OK, $check);

        $pwGenClass->setNeedNumbers(0);
        $check = $method->invoke($pwGenClass, 'a4bc13');
        $this->assertEquals($pwGenClass::PASSWORD_OK, $check);


        $check = $method->invoke($pwGenClass, 'abcabcd');
        $this->assertEquals($pwGenClass::PASSWORD_OK, $check);
    }

    public function testChecksLower()
    {
        $pwGenClass = new PasswordStrengthChecker();

        $pwGen = new \ReflectionClass(get_class($pwGenClass));
        $pwGenClass->setMinLength(1);
        $pwGenClass->setMinUniqueChars(1);

        $method = $pwGen->getMethod('checkPassword');
        $method->setAccessible(true);

        $pwGenClass->setNeedLowerCase(3);
        $check = $method->invoke($pwGenClass, 'abc13');
        $this->assertEquals($pwGenClass::PASSWORD_OK, $check);

        $check = $method->invoke($pwGenClass, 'A4BC13');
        $this->assertEquals($pwGenClass::LOWERCASE, $check);

        $pwGenClass->setNeedLowerCase(0);
        $check = $method->invoke($pwGenClass, 'a4bc13');
        $this->assertEquals($pwGenClass::PASSWORD_OK, $check);

        $check = $method->invoke($pwGenClass, 'ABCABCD');
        $this->assertEquals($pwGenClass::PASSWORD_OK, $check);
    }


    public function testCheckUpper()
    {
        $pwGenClass = new PasswordStrengthChecker();

        $pwGen = new \ReflectionClass(get_class($pwGenClass));
        $pwGenClass->setMinLength(1);
        $pwGenClass->setMinUniqueChars(1);

        $method = $pwGen->getMethod('checkPassword');
        $method->setAccessible(true);

        $pwGenClass->setNeedUpperCase(3);
        $check = $method->invoke($pwGenClass, 'abc13');
        $this->assertEquals($pwGenClass::UPPERCASE, $check);

        $check = $method->invoke($pwGenClass, 'aDbc13B');
        $this->assertEquals($pwGenClass::UPPERCASE, $check);

        $check = $method->invoke($pwGenClass, 'A4BC13');
        $this->assertEquals($pwGenClass::PASSWORD_OK, $check);

        $pwGenClass->setNeedUpperCase(1);
        $check = $method->invoke($pwGenClass, 'a4Bc13');
        $this->assertEquals($pwGenClass::PASSWORD_OK, $check);

        $check = $method->invoke($pwGenClass, 'ABCABC');
        $this->assertEquals($pwGenClass::PASSWORD_OK, $check);
    }

    public function testCheckMinLength()
    {
        $pwGenClass = new PasswordStrengthChecker();
        $pwGen = new \ReflectionClass(get_class($pwGenClass));

        $method = $pwGen->getMethod('checkPassword');
        $method->setAccessible(true);

        $pwGenClass->setMinLength(8);
        $check = $method->invoke($pwGenClass, 'abc13');
        $this->assertEquals($pwGenClass::LENGTH, $check);

        $pwGenClass->setMinLength(5);
        $check = $method->invoke($pwGenClass, 'abc13');
        $this->assertEquals($pwGenClass::PASSWORD_OK, $check);

        $pwGenClass->setMinLength(8);
        $check = $method->invoke($pwGenClass, 'abc1345A');
        $this->assertEquals($pwGenClass::PASSWORD_OK, $check);
    }

    public function testCheckBlackList()
    {
        $pwGenClass = new PasswordStrengthChecker();

        $pwGenClass->addToBlacklist('blacklist');

        $this->assertEquals($pwGenClass::BLACKLIST_WORD, $pwGenClass->checkPassword('123blacklist'));
        $this->assertEquals($pwGenClass::BLACKLIST_WORD, $pwGenClass->checkPassword('123Blacklist'));
        $this->assertEquals($pwGenClass::BLACKLIST_WORD, $pwGenClass->checkPassword('123blackList'));
        $this->assertEquals($pwGenClass::BLACKLIST_WORD, $pwGenClass->checkPassword('blacklist'));
        $this->assertEquals($pwGenClass::BLACKLIST_WORD, $pwGenClass->checkPassword('Blacklist'));
        $this->assertEquals($pwGenClass::BLACKLIST_WORD, $pwGenClass->checkPassword('blaCklist456'));

        $this->assertEquals($pwGenClass::PASSWORD_OK, $pwGenClass->checkPassword('thisoneisok'));
    }

    public function testCheckUnique()
    {
        $pwgen = new PasswordStrengthChecker();
        $pwgen->setMinLength(1);
        $pwgen->setMinUniqueChars(3);

        $this->assertEquals($pwgen::UNIQUE_CHARS, $pwgen->checkPassword('111'));
        $this->assertEquals($pwgen::UNIQUE_CHARS, $pwgen->checkPassword('1a1a1'));
        $this->assertEquals($pwgen::UNIQUE_CHARS, $pwgen->checkPassword('1Aa1'));
        $this->assertEquals($pwgen::UNIQUE_CHARS, $pwgen->checkPassword('111Aa'));
        $this->assertEquals($pwgen::PASSWORD_OK, $pwgen->checkPassword('AaBbCc2'));
    }
}
