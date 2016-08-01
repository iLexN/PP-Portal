<?php

namespace PP\Test;

class UserModuleTest extends \PHPUnit_Framework_TestCase
{
    protected $UserPolicyModule;

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


        $UserPolicyModule = new \PP\Portal\Module\UserPolicyModule($c);

        $this->UserPolicyModule = $UserPolicyModule;
    }

    public function testGetPolicyList()
    {
        $this->assertTrue($this->UserPolicyModule->getUerPolicy(1));
        $this->assertInstanceOf(\PP\Portal\DbModel\UserPolicy::class, $this->UserPolicyModule->userPolicy);

        $this->assertFalse($this->UserPolicyModule->getUerPolicy(1000));
    }
}
