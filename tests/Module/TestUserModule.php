<?php

namespace PP\Test;

class TestUserModule extends \PHPUnit_Framework_TestCase
{
    private $c;
    
    public function testIsUserExistByID()
    {
        $userModule = new \PP\Portal\Module\UserModule($this->setUpContainer());

        $this->assertTrue($userModule->isUserExistByID(1));

        $this->assertEquals(1,$userModule->client->Client_NO);
    }
    
    public function testIsUserNotFind()
    {
        $userModule = new \PP\Portal\Module\UserModule($this->setUpContainer());

        $this->assertFalse($userModule->isUserExistByID(100));
    }
    
    public function setUpContainer()
    {
        $app = new \Slim\App();
        $c = $app->getContainer();

        $c['pool'] = function ($c) {
            
            $settings = [
                'path' => __DIR__ . '/../cache/data',
            ];

            $driver = new \Stash\Driver\FileSystem($settings);

            return new \Stash\Pool($driver);
        };

        $c['dataCacheConfig']= ['expiresAfter'=>3600];

        return $c;

    }
    

}
