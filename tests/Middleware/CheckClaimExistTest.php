<?php

namespace PP\Test;

class CheckClaimExistTest extends \PHPUnit\Framework\TestCase
{
    protected $action;
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
                '6010' => [
                    'code'  => 6010,
                ],
            ];
        };

        $c['ViewHelper'] = function ($c) {
            return new \PP\Portal\Module\Helper\View($c);
        };
        $c['ClaimModule'] = function (\Slim\Container $c) {
            return new \PP\Portal\Module\ClaimModule($c);
        };

        $environment = \Slim\Http\Environment::mock([]);
        $this->request = \Slim\Http\Request::createFromEnvironment($environment);
        $this->response = new \Slim\Http\Response();

        $this->action = new \PP\Portal\Middleware\CheckClaimExist($c);
    }

    public function testCheckUsePolicyrExist()
    {
        $action = $this->action;

        $route = $this->createMock(\Slim\Route::class);
        $route->method('getArguments')->willReturn(['id' => 1]);

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

    public function testCheckUsePolicyrExistError()
    {
        $action = $this->action;

        $route = $this->createMock(\Slim\Route::class);
        $route->method('getArguments')->willReturn(['id' => 22020]);

        $request = $this->request;
        $request = $request->withAttribute('route', $route);

        $response = $this->response;

        $response = $action($request, $response, function ($request, $response) {
            return $response->write(json_encode(['success' => true]));
        });

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(6010, $out['status_code']);
    }
}
