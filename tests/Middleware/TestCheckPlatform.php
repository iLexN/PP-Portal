<?php

namespace PP\Test;

class TestCheckPlatform extends \PHPUnit_Framework_TestCase
{
    private $c;
    
    public function testCheckPlatformError()
    {
        $action = new \PP\Middleware\CheckPlatform($this->setUpContainer());

        $request = $this->setUpRequest();

        $request = $request->withHeader('PP-Portal-Platform', 'k');

        $response = new \Slim\Http\Response();

        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success'=>true]));
        });

        $this->assertJsonStringEqualsJsonString(
            json_encode(['errors' => [
                        'status' => 403,
                        'title'  => 'Platform Header Missing',
                    ]]),
                    json_encode(json_decode((string)$response->getBody()))
        );
    }

    function testWeb(){
        $action = new \PP\Middleware\CheckPlatform($this->setUpContainer());

        $request = $this->setUpRequest();

        $request = $request->withHeader('PP-Portal-Platform', 'Web');

        $response = new \Slim\Http\Response();

        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success'=>true]));
        });

        $this->assertJsonStringEqualsJsonString(
            json_encode(['success'=>true]),
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
