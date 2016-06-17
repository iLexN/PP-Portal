<?php

namespace PP\Test;

class TestPasswordModule extends \PHPUnit_Framework_TestCase
{
    public function testValidateLength()
    {
        $p = $this->setUpPasswordModule();

        $this->assertTrue($p->validateLength('123456', 6));
        $this->assertFalse($p->validateLength('12345', 6));
    }

    public function testValidateLetters()
    {
        $p = $this->setUpPasswordModule();

        $this->assertTrue($p->validateLetters('abc'));
        $this->assertFalse($p->validateLetters('12345'));
    }

    public function testValidateNumbers()
    {
        $p = $this->setUpPasswordModule();

        $this->assertTrue($p->validateNumbers('123'));
        $this->assertFalse($p->validateNumbers('abc'));
    }

    public function testValidateCaseDiff()
    {
        $p = $this->setUpPasswordModule();

        $this->assertTrue($p->validateCaseDiff('1a2A3'));
        $this->assertFalse($p->validateCaseDiff('abc123'));
        $this->assertFalse($p->validateCaseDiff('AA123'));
    }

    public function testValidateSymbols()
    {
        $p = $this->setUpPasswordModule();

        $this->assertTrue($p->validateSymbols('@'));
        $this->assertTrue($p->validateSymbols('-'));
        $this->assertTrue($p->validateSymbols('_'));
        $this->assertTrue($p->validateSymbols('#'));
        $this->assertTrue($p->validateSymbols('('));
        $this->assertTrue($p->validateSymbols(')'));
        $this->assertTrue($p->validateSymbols('$'));
        $this->assertTrue($p->validateSymbols('%'));
        $this->assertTrue($p->validateSymbols('!'));
        $this->assertTrue($p->validateSymbols('@'));
        $this->assertTrue($p->validateSymbols('^'));
        $this->assertTrue($p->validateSymbols('*'));
        $this->assertFalse($p->validateSymbols('abc123'));
    }

    public function testC1()
    {
        $p = $this->setUpPasswordModule();

        $this->assertTrue($p->validateCaseDiff('1a2A3') && $p->validateLength('123456', 6));
        $this->assertFalse($p->validateCaseDiff('1A2A3') && $p->validateLength('123456', 6));
        $this->assertFalse($p->validateCaseDiff('1a2A3') && $p->validateLength('1256', 6));
    }

    public function testC2()
    {
        $p = $this->setUpPasswordModule();

        $pass = 'aa12bdck';

        $this->assertTrue($p->validateNumbers($pass) &&
                $p->validateLength($pass, 6) &&
                $p->validateLetters($pass));
    }

    public function setUpPasswordModule()
    {
        $c = new \Slim\Container();

        $p = new \PP\Portal\Module\PasswordModule($c);

        return $p;
    }
}
