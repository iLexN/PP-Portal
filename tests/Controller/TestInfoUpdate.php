<?php

namespace PP\Test;

class TestInfoUpdate extends \PHPUnit_Framework_TestCase
{
    private $c;

    public function testFieldnotMatch()
    {
        $c = $this->setUpContainer();

        $action = new \PP\Portal\Controller\User\InfoUpdate($c);

        $_POST = ['aa'=>'aa'];
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD' => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;'
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = new \Slim\Http\Response();

        $response = $action($request, $response, []);

         $this->assertJsonStringEqualsJsonString(
            json_encode(['errors' => [
                'title' => 'Field(s) not match',
            ]]),
                    json_encode(json_decode((string) $response->getBody()))
        );

    }

    public function testInofUpdate()
    {
        $c = $this->setUpContainer();

        $action = new \PP\Portal\Controller\User\InfoUpdate($c);

        $_POST = ['Home_Address_2'=>'Home_Address_2'];
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD' => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;'
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = new \Slim\Http\Response();

        $response = $action($request, $response, []);

         $this->assertJsonStringEqualsJsonString(
            json_encode(['data' => [
                'title' => 'User Info Updated',
            ]]),
                    json_encode(json_decode((string) $response->getBody()))
        );
         
        $this->assertEquals($c['UserModule']->client->Home_Address_2, 'Home_Address_2');

    }
    

    public function setUpContainer()
    {
        $c = new \Slim\Container();

        $c['jsonConfig'] = ['prettyPrint' => false];

        $c['ViewHelper'] = function ($c) {
            return new \PP\Portal\Module\Helper\View($c);
        };

        $c['UserModule'] = function($c){
            return new \PP\Portal\Module\UserModule($c);
        };

        $c['pool'] = function ($c) {
            $settings = [
                'path' => __DIR__.'/../cache/data',
            ];
            $driver = new \Stash\Driver\FileSystem($settings);

            return new \Stash\Pool($driver);
        };

        $c['dataCacheConfig'] = ['expiresAfter' => 3600];

        $c['logger'] = function($c) {
            $logger = $this->getMockBuilder(\Monolog\Logger::class)
                    ->setMethods(['error'])
                    ->disableOriginalConstructor()
                    ->getMock();
            return $logger;
        };

        $c['UserModule']->isUserExistByID(1);

        return $c;
    }
}
