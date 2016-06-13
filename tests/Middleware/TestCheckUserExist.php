<?php

namespace PP\Test;

class TestCheckUserExist extends \PHPUnit_Framework_TestCase
{
    private $c;
    
    public function testCheckUserExist()
    {
        $c =$this->setUpContainer();

        $c['UserModule'] = function(){
            $userModule = $this->getMockBuilder(UserModule::class)
                ->setMethods(['isUserExistByID'])
                ->disableOriginalConstructor()
                ->getMock();
            $userModule->expects($this->once())
                ->method('isUserExistByID')
                ->willReturn(TRUE);
            return $userModule;
        };

        $action = new \PP\Middleware\CheckUserExist($c);

        $route = $this->getMockBuilder(Route::class)
                ->setMethods(['getArguments'])
                ->disableOriginalConstructor()
                ->getMock();
        $route->method('getArguments')->willReturn(['id'=>1]);

        $request = $this->setUpRequest();
        $request = $request->withAttribute('route', $route);

        $response = new \Slim\Http\Response();

        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success'=>true]));
        });

        $this->assertJsonStringEqualsJsonString(
            json_encode(['success'=>true]),
            json_encode(json_decode((string)$response->getBody()))
        );
    }

    public function testCheckUserError()
    {
        $c =$this->setUpContainer();

        $c['UserModule'] = function(){
            $userModule = $this->getMockBuilder(UserModule::class)
                ->setMethods(['isUserExistByID'])
                ->disableOriginalConstructor()
                ->getMock();
            $userModule->expects($this->once())
                ->method('isUserExistByID')
                ->willReturn(false);
            return $userModule;
        };

        $action = new \PP\Middleware\CheckUserExist($c);

        $route = $this->getMockBuilder(Route::class)
                ->setMethods(['getArguments'])
                ->disableOriginalConstructor()
                ->getMock();
        $route->method('getArguments')->willReturn(['id'=>1]);

        $request = $this->setUpRequest();
        $request = $request->withAttribute('route', $route);

        $response = new \Slim\Http\Response();

        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success'=>true]));
        });

        $this->assertJsonStringEqualsJsonString(
            json_encode(['errors' => [
                'title' => 'User Not Found',
            ]]),
            json_encode(json_decode((string)$response->getBody()))
        );
    }

    public function setUpRequest(){
        $environment = \Slim\Http\Environment::mock([]);

        return  \Slim\Http\Request::createFromEnvironment($environment);
    }

    public function setUpContainer()
    {
        $app = new \Slim\App();
        $c = $app->getContainer();

        $c['jsonConfig'] = ['prettyPrint'=>false];

        $c['ViewHelper'] = function ($c) {
            return new \PP\Module\Helper\View($c);
        };

        return $c;

    }
    
}
