<?php

namespace PP\Test;

class TestHttpBasicAuth extends \PHPUnit_Framework_TestCase
{
    private $c;
    
    public function testCheckPlatformSuccess()
    {
        $action = new \PP\Middleware\HttpBasicAuth($this->setUpContainer());

        $_SERVER['PHP_AUTH_USER'] = 'user';
        $_SERVER['PHP_AUTH_PW'] = 'pass';

        $request = \Slim\Http\Request::createFromEnvironment(new \Slim\Http\Environment($_SERVER));

        $response = new \Slim\Http\Response();
        

        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success'=>true]));
        });

        $this->assertJsonStringEqualsJsonString(
            json_encode(['success'=>true]),
            json_encode(json_decode((string)$response->getBody()))
        );
    }

    public function testCheckPlatformError()
    {
        $action = new \PP\Middleware\HttpBasicAuth($this->setUpContainer());

        $_SERVER['PHP_AUTH_USER'] = 'user111';
        $_SERVER['PHP_AUTH_PW'] = 'pass222';

        $request = \Slim\Http\Request::createFromEnvironment(new \Slim\Http\Environment($_SERVER));

        $response = new \Slim\Http\Response();


        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success'=>true]));
        });

        $this->assertJsonStringEqualsJsonString(
            json_encode(['errors' => [
                        'status' => 401,
                        'title'  => 'Need Authenticate',
                    ]]),
            json_encode(json_decode((string)$response->getBody()))
        );

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('WWW-Authenticate'));

    }
    
    public function setUpContainer()
    {
        $app = new \Slim\App();
        $c = $app->getContainer();

        $c['firewallConfig'] = [
                'username'=>'user',
                'password'=>'pass'
            ];

        $c['jsonConfig'] = ['prettyPrint'=>false];

        $c['ViewHelper'] = function ($c) {
            return new \PP\Module\Helper\View($c);
        };

        return $c;

    }
    

}
