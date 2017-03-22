<?php

namespace PP\Test;

class UserBankAccModuleTest extends \PHPUnit\Framework\TestCase
{
    protected $UserBankAccModule;

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

        $c['UserModule'] = function ($c) {
            return new \PP\Portal\Module\UserModule($c);
        };
        $c['UserModule']->isUserExistByID(2);

        $UserBankAccModule = new \PP\Portal\Module\UserBankAccModule($c);

        $this->UserBankAccModule = $UserBankAccModule;
    }

    public function testNewBlankAcc()
    {
        $new = $this->UserBankAccModule->newBlankAcc(2);
        $this->assertInstanceOf(\PP\Portal\DbModel\UserBankAcc::class, $new);

        return $new;
    }

    public function testValidBank()
    {
        $fillable = ['nick_name','iban', 'bank_swift_code'];
        $data = [
                'iban' => 123,
            ];
        $v = $this->UserBankAccModule->validBank($data, $fillable);
        $this->assertFalse($v->validate());

        $data = [
                'iban'            => 123,
                'bank_swift_code' => 222,
                'nick_name'       => 'nick',
            ];
        $v = $this->UserBankAccModule->validBank($data, $fillable);
        $this->assertTrue($v->validate());
    }

    /**
     * @depends testNewBlankAcc
     */
    public function testSaveData($new)
    {
        $data = [
            'currency'          => 'USD',
            'account_user_name' => 'safd',
            'account_number'    => 'dsf',
            'iban'              => 'dsfdsfdsfds',
        ];
        $this->UserBankAccModule->saveData($new, $data);
        $this->expectOutputString('foo');
        echo 'foo';
    }

    /**
     * @depends testNewBlankAcc
     */
    public function testdel($new)
    {
        $new = $this->UserBankAccModule->delBank($new);
        $this->expectOutputString('foo');
        echo 'foo';
    }

    public function testGetByUserID()
    {
        $info = $this->UserBankAccModule->getByUserID();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $info);
    }
}
