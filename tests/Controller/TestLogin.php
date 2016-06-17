<?php

namespace PP\Test;

class TestLogin extends \PHPUnit_Framework_TestCase
{
    private $c;

    public function testUserFound()
    {
        $action = new \PP\Portal\Controller\User\Login($this->setUpContainer());

        $_POST['clientID'] = '1';
        $_POST['password'] = 'alex';
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
                'id' => 1,
                ]]),
                    json_encode(json_decode((string) $response->getBody()))
        );
    }

    public function testUserNotFound()
    {
        $action = new \PP\Portal\Controller\User\Login($this->setUpContainer());

        $_POST['clientID'] = '10000000';
        $_POST['password'] = 'alex';
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
            'title' => 'Login User Not Found',
        ]]),
                    json_encode(json_decode((string) $response->getBody()))
        );
    }

    public function testMissFields()
    {
        $action = new \PP\Portal\Controller\User\Login($this->setUpContainer());

        
        
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD' => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;'
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        

        $response = new \Slim\Http\Response();

        $response = $action($request, $response, []);

        $this->assertJsonStringEqualsJsonString(
            json_encode(['errors' => [
                'title' => 'Missing field(s)',
            ]]),
                    json_encode(json_decode((string) $response->getBody()))
        );
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

        return $c;
    }
}
