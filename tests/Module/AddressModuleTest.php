<?php

namespace PP\Test;

class AddressModuleTest extends \PHPUnit\Framework\TestCase
{
    protected $addressModule;

    protected $address;

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
            $userModule = new \PP\Portal\Module\UserModule($c);
            $userModule->isUserExistByID(2);

            return $userModule;
        };

        $this->addressModule = new \PP\Portal\Module\AddressModule($c);
        $this->address = new \PP\Portal\DbModel\Address();
    }

    public function testSetValidator()
    {
        $data = [
            'nick_name'         => 't',
        ];
        $v = $this->addressModule->setValidator($data, $this->address);
        $this->assertInstanceOf(\Valitron\Validator::class, $v);
    }

    public function testSaveData()
    {
        $data = [
            'nick_name'         => 't',
        ];
        $v = $this->addressModule->save($data, $this->address);
        $this->expectOutputString('foo');
        echo 'foo';
    }
}
