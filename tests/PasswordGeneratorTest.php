<?php

namespace Npluis\PasswordGenerator;

class PasswordGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $pwGen = new PasswordGenerator();

        $password = $pwGen->create(6,10);
        $this->assertGreaterThanOrEqual(6, strlen($password));
        $this->assertLessThanOrEqual(10, strlen($password));

        $password = $pwGen->create(4, 4);
        $this->assertEquals(4, strlen($password));
    }


    public function testFailGenerate()
    {
        $pwGen = new PasswordGenerator();
        $this->expectException(\Exception::class);
        $password = $pwGen->create(1);

        $this->expectException(\Exception::class);
        $password = $pwGen->create(3, 1);

        $this->expectException(\Exception::class);
        $password = $pwGen->create(65, 128);
    }






    public function testSpecial()
    {
        $pwgen = new PasswordGenerator();
        $default = $pwgen->getSpecial();
        $pwgen->setAllowSpecial(false);
        $this->assertEquals('', $pwgen->getSpecial());

        //set to on again
        $pwgen->setAllowSpecial(true);
        $this->assertEquals($default, $pwgen->getSpecial());

        $pwgen->setSpecial($default.'/');
        $this->assertEquals($default.'/', $pwgen->getSpecial());
        $pwgen->setAllowSpecial(false);
        $this->assertEquals('', $pwgen->getSpecial());
        $pwgen->setAllowSpecial(true);
        $this->assertEquals($default.'/', $pwgen->getSpecial());
    }

    public function testBrackets()
    {
        $pwgen = new PasswordGenerator();
        $default = $pwgen->getBrackets();
        $pwgen->setAllowBrackets(false);
        $this->assertEquals('', $pwgen->getBrackets());

        //set to on again
        $pwgen->setAllowBrackets(true);
        $this->assertEquals($default, $pwgen->getBrackets());
    }
}
