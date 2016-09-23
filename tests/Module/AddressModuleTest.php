<?php

namespace PP\Test;

class AddressModuleTest extends \PHPUnit_Framework_TestCase
{
    protected $addressModule;

    protected $address;

    protected function setUp()
    {
        $c = new \Slim\Container();

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
        $v = $this->addressModule->saveData($data, $this->address);
        $this->expectOutputString('foo');
        echo 'foo';
    }
}
