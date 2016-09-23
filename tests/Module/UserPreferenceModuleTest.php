<?php

namespace PP\Test;

class UserPreferenceModuleTest extends \PHPUnit_Framework_TestCase
{
    protected $userPreferenceModule;

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

        $this->userPreferenceModule = new \PP\Portal\Module\UserPreferenceModule($c);
    }

    public function testNewPreference()
    {
        $info = $this->userPreferenceModule->newPreference();
        $this->assertInstanceOf(\PP\Portal\DbModel\UserPreference::class, $info);
    }

    public function testGetByUserID()
    {
        $info = $this->userPreferenceModule->getByUserID();
        $this->assertInstanceOf(\PP\Portal\DbModel\UserPreference::class, $info);
    }
}
