<?php

namespace PP\Test;

class CheckUserExistTest extends \PHPUnit_Framework_TestCase
{
    protected $c;
    protected $request;
    protected $response;

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

        $c['jsonConfig'] = ['prettyPrint' => false];
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '2010' => [
                    'code'  => 2010,
                ],
            ];
        };

        $c['ViewHelper'] = function ($c) {
            return new \PP\Portal\Module\Helper\View($c);
        };
        $c['UserModule'] = function (\Slim\Container $c) {
            return new \PP\Portal\Module\UserModule($c);
        };
        $this->c = $c;

        $environment = \Slim\Http\Environment::mock([]);
        $this->request = \Slim\Http\Request::createFromEnvironment($environment);
        $this->response = new \Slim\Http\Response();
    }

    public function testCheckUserExist()
    {
        $c = $this->c;
        $action = new \PP\Portal\Middleware\CheckUserExist($c);

        $route = $this->createMock(Route::class);
        $route->method('getArguments')->willReturn(['id' => 2]);

        $request = $this->request;
        $request = $request->withAttribute('route', $route);

        $response = $this->response;

        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success' => true]));
        });

        $this->assertJsonStringEqualsJsonString(
            json_encode(['success' => true]),
            json_encode(json_decode((string) $response->getBody()))
        );
    }

    public function testCheckUserError()
    {
        $c = $this->c;

        $action = new \PP\Portal\Middleware\CheckUserExist($c);

        $route = $this->createMock(Route::class);
        $route->method('getArguments')->willReturn(['id' => 1]);

        $request = $this->request;
        $request = $request->withAttribute('route', $route);

        $response = $this->response;

        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success' => true]));
        });

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2010, $out['status_code']);
    }
}
