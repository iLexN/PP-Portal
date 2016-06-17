<?php

namespace PP\Test;

class TestCheckPlatform extends \PHPUnit_Framework_TestCase
{
    private $c;

    public function testCheckPlatformError()
    {
        $action = new \PP\Portal\Middleware\CheckPlatform($this->setUpContainer());

        $request = $this->setUpRequest();

        $request = $request->withHeader('PP-Portal-Platform', 'k');

        $response = new \Slim\Http\Response();

        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success' => true]));
        });

        $this->assertJsonStringEqualsJsonString(
            json_encode(['errors' => [
                        'status' => 403,
                        'title'  => 'Platform Header Missing',
                    ]]),
            json_encode(json_decode((string) $response->getBody()))
        );
    }

    public function testWeb()
    {
        $action = new \PP\Portal\Middleware\CheckPlatform($this->setUpContainer());

        $request = $this->setUpRequest();

        $request = $request->withHeader('PP-Portal-Platform', 'Web');

        $response = new \Slim\Http\Response();

        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success' => true]));
        });

        $this->assertJsonStringEqualsJsonString(
            json_encode(['success' => true]),
            json_encode(json_decode((string) $response->getBody()))
        );
    }

    public function setUpRequest()
    {
        $environment = \Slim\Http\Environment::mock([]);

        return  \Slim\Http\Request::createFromEnvironment($environment);
    }

    public function testCheckPlatformMiss()
    {
        $action = new \PP\Portal\Middleware\CheckPlatform($this->setUpContainer());

        $request = $this->setUpRequest();

        $request = $request->withHeader('miss', 'k');

        $response = new \Slim\Http\Response();

        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success' => true]));
        });

        $this->assertJsonStringEqualsJsonString(
            json_encode(['errors' => [
                        'status' => 403,
                        'title'  => 'Platform Header Missing',
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

        return $c;
    }
}
