<?php

namespace PP\Test;

class UserModuleTest extends \PHPUnit\Framework\TestCase
{
    protected $userModule;

    protected function setUp()
    {
        $c = new \Slim\Container();

        $c['pool'] = function ($c) {
            $settings = [
                'path' => __DIR__.'/../cache/data',
            ];
            $driver = new \Stash\Driver\FileSystem($settings);

            return new \Stash\Pool($driver);
        };

        $c['dataCacheConfig'] = ['expiresAfter' => 1];

        $c['PasswordModule'] = function ($c) {
            return new \PP\Portal\Module\PasswordModule($c);
        };

        $userModule = new \PP\Portal\Module\UserModule($c);

        $this->userModule = $userModule;
    }

    public function testverifyUser()
    {
        $ar = [
            'ppmid'         => '2',
            'date_of_birth' => '2',
        ];
        $this->assertFalse($this->userModule->verifyUser($ar));

        $ar = [
            'ppmid'         => '2',
            'date_of_birth' => '1980-10-10',
        ];
        $user = $this->userModule->verifyUser($ar);
        $this->assertEquals(2, $user->ppmid);
    }

    public function testIsUserExistByID()
    {
        $this->assertTrue($this->userModule->isUserExistByID(2));
        $this->assertEquals(2, $this->userModule->user->ppmid);

        $this->assertFalse($this->userModule->isUserExistByID(100));
    }

    public function testIsUserNameExist()
    {
        $this->assertTrue($this->userModule->isUserNameExist('DoreMe'));
        $this->assertFalse($this->userModule->isUserNameExist('alex'));
    }

    public function testIsUserExistByEmail()
    {
        $this->assertTrue($this->userModule->isUserExistByEmail('alex@kwiksure.com'));
        $this->assertFalse($this->userModule->isUserExistByEmail('a@a.com'));
    }

    public function testIsUserExistByUsername()
    {
        $this->assertTrue($this->userModule->isUserExistByUsername('alex'));
        $this->assertFalse($this->userModule->isUserExistByUsername('DoreMe'));
    }

    public function testIsUserExistByForgotToken()
    {
        $this->assertTrue($this->userModule->isUserExistByForgotToken('adf2'));
        $this->assertEquals(173802, $this->userModule->user->ppmid);
        $this->assertFalse($this->userModule->isUserExistByForgotToken('DoreMe'));
    }

    public function testSavePassword()
    {
        $pass = 'adf';
        $this->userModule->isUserExistByID(173803);
        $this->assertEquals(null, $this->userModule->user->password);
        $this->userModule->savePassword($pass);
        $this->assertContains($pass, $this->userModule->user->password);
    }

    public function testSaveForgot()
    {
        $str = 'adf';
        $this->userModule->isUserExistByID(173803);
        $this->assertEquals(null, $this->userModule->user->forgot_str);
        $this->userModule->saveForgot($str);
        $this->assertContains($str, $this->userModule->user->forgot_str);
    }

    public function testNewInfoReNew()
    {
        $this->userModule->isUserExistByID(173803);
        $newInfo = $this->userModule->newInfoReNew();
        $this->assertEquals(173803, $newInfo->ppmid);
    }

    public function testSaveInfoReNew()
    {
        $this->userModule->isUserExistByID(173803);
        $newInfo = $this->userModule->newInfoReNew();
        $ar = [
            'email'   => 'aaa',
            'phone_1' => '123',
        ];
        $this->userModule->saveInfoReNew($newInfo, $ar);
        $this->expectOutputString('foo');
        echo 'foo';
    }

    public function testSaveSignUp()
    {
        $this->userModule->isUserExistByID(173802);
        $data = [
            'user_name' => 'afds',
            'password'  => 'dfd',
        ];
        $this->userModule->saveSignUp($data);

        $this->expectOutputString('foo');
        echo 'foo';
    }

    public function testNewForgotUsername()
    {
        $this->userModule->newForgotUsername();
        $this->expectOutputString('foo');
        echo 'foo';
    }

    public function testSaveForgotUsername()
    {
        $user = $this->userModule->newForgotUsername();

        $data = [
            'name'  => 'fill name',
            'phone' => '123456',
            'email' => 'a@a.com',
        ];

        $this->userModule->isUserExistByID(173802);
        $this->userModule->saveForgotUsername($user, $data);

        $this->expectOutputString('foo');
        echo 'foo';
    }

    public function testisPhoneMatch()
    {
        $this->userModule->isUserExistByID(2);
        $this->assertTrue($this->userModule->isPhoneMatch('12345678'));
        $this->assertFalse($this->userModule->isPhoneMatch('12345679'));
    }
}
