<?php

namespace PP\Test;

class HttpBasicAuthTest extends \PHPUnit_Framework_TestCase
{
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '4020' => [
                    'code'  => 4020,
                ],
            ];
        };
        $c['jsonConfig'] = ['prettyPrint' => false];
        $c['ViewHelper'] = function (\Slim\Container $c) {
            return new \PP\Portal\Module\Helper\View($c);
        };
        $c['firewallConfig'] = [
                'username' => 'user',
                'password' => 'pass',
            ];

        $this->action = new \PP\Portal\Middleware\HttpBasicAuth($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testCheckPlatformSuccess()
    {
        $action = $this->action;

        $_SERVER['PHP_AUTH_USER'] = 'user';
        $_SERVER['PHP_AUTH_PW'] = 'pass';

        $request = \Slim\Http\Request::createFromEnvironment(new \Slim\Http\Environment($_SERVER));

        $response = $this->response;

        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success' => true]));
        });

        $this->assertJsonStringEqualsJsonString(
            json_encode(['success' => true]),
            json_encode(json_decode((string) $response->getBody()))
        );
    }

    public function testCheckPlatformError()
    {
        $action = $this->action;

        $_SERVER['PHP_AUTH_USER'] = 'user111';
        $_SERVER['PHP_AUTH_PW'] = 'pass222';

        $request = \Slim\Http\Request::createFromEnvironment(new \Slim\Http\Environment($_SERVER));

        $response = $this->response;

        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success' => true]));
        });

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(4020, $out['status_code']);

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('WWW-Authenticate'));
    }
}
