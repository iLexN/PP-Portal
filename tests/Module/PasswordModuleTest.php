<?php

namespace PP\Test;

class PasswordModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testp()
    {
        $c = new \Slim\Container();

        $p = new \PP\Portal\Module\PasswordModule($c);

        $this->expectOutputString('foo');
        echo 'foo';

        return $p;
    }

    /**
     * @depends testp
     */
    public function testValidateLength($p)
    {
        $this->assertTrue($p->validateLength('123456', 6));
        $this->assertFalse($p->validateLength('12345', 6));
    }

    /**
     * @depends testp
     */
    public function testValidateLetters($p)
    {
        $this->assertTrue($p->validateLetters('abc'));
        $this->assertFalse($p->validateLetters('12345'));
    }

    /**
     * @depends testp
     */
    public function testValidateNumbers($p)
    {
        $this->assertTrue($p->validateNumbers('123'));
        $this->assertFalse($p->validateNumbers('abc'));
    }

    /**
     * @depends testp
     */
    public function testValidateCaseDiff($p)
    {
        $this->assertTrue($p->validateCaseDiff('1a2A3'));
        $this->assertFalse($p->validateCaseDiff('abc123'));
        $this->assertFalse($p->validateCaseDiff('AA123'));
    }

    /**
     * @depends testp
     */
    public function testValidateSymbols($p)
    {
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

    /**
     * @depends testp
     */
    public function testC1($p)
    {
        $this->assertTrue($p->validateCaseDiff('1a2A3') && $p->validateLength('123456', 6));
        $this->assertFalse($p->validateCaseDiff('1A2A3') && $p->validateLength('123456', 6));
        $this->assertFalse($p->validateCaseDiff('1a2A3') && $p->validateLength('1256', 6));
    }

    /**
     * @depends testp
     */
    public function testC2($p)
    {
        $pass = 'aa12bdck';

        $this->assertTrue($p->validateNumbers($pass) &&
                $p->validateLength($pass, 6) &&
                $p->validateLetters($pass));
    }

    /**
     * @depends testp
     */
    public function testValidateLengthBetween($p)
    {
        $this->assertTrue($p->validateLengthBetween('1a2A3dsf', 1, 8));
        $this->assertFalse($p->validateLengthBetween('1a2A3dsf', 1, 6));
    }

    /**
     * @depends testp
     */
    public function testIsStrongPassword($p)
    {
        $this->assertTrue($p->isStrongPassword('1a2A3dsf'));
        $this->assertFalse($p->isStrongPassword('1a23dsf'));
        $this->assertFalse($p->isStrongPassword('Aadsf'));
    }

    /**
     * @depends testp
     */
    public function testPasswordHash($p)
    {
        $this->assertContains('$2y$10', $p->passwordHash('1a2A3dsf'));
    }
}
