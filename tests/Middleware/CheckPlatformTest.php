<?php

namespace PP\Test;

class CheckPlatformTest extends \PHPUnit\Framework\TestCase
{
    protected $action;
    protected $request;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '4010' => [
                    'code'  => 4010,
                ],
            ];
        };
        $c['jsonConfig'] = ['prettyPrint' => false];
        $c['ViewHelper'] = function (\Slim\Container $c) {
            return new \PP\Portal\Module\Helper\View($c);
        };

        $this->action = new \PP\Portal\Middleware\CheckPlatform($c);

        $environment = \Slim\Http\Environment::mock([]);
        $this->request = \Slim\Http\Request::createFromEnvironment($environment);
        $this->response = new \Slim\Http\Response();
    }

    public function testCheckPlatformError()
    {
        $action = $this->action;

        $request = $this->request;
        $request = $request->withHeader('PP-Portal-Platform', 'k');

        $response = $this->response;
        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success' => true]));
        });

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(4010, $out['status_code']);
    }

    public function testWeb()
    {
        $action = $this->action;

        $request = $this->request;
        $request = $request->withHeader('PP-Portal-Platform', 'Web');

        $response = $this->response;
        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success' => true]));
        });

        $this->assertJsonStringEqualsJsonString(
            json_encode(['success' => true]),
            json_encode(json_decode((string) $response->getBody()))
        );
    }

    public function testCheckPlatformMiss()
    {
        $action = $this->action;

        $request = $this->request;
        $request = $request->withHeader('miss', 'k');

        $response = $this->response;
        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success' => true]));
        });

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(4010, $out['status_code']);
    }
}
