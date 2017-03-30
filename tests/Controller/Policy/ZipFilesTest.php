<?php

namespace PP\Test\Policy;

class ZipFilesTest extends \PHPUnit\Framework\TestCase
{
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();

        $c['dataCacheConfig'] = ['expiresAfter' => 1];

        $c['pool'] = function () {
            $settings = [
                'path' => __DIR__.'/../../cache/data',
            ];
            $driver = new \Stash\Driver\FileSystem($settings);

            return new \Stash\Pool($driver);
        };

        $c['ClaimFileModule'] = function ($c) {
            return new \PP\Portal\Module\ClaimFileModule($c);
        };
        $c['UserPolicyModule'] = function ($c) {
            return new \PP\Portal\Module\UserPolicyModule($c);
        };
        $c['UserPolicyModule']->getUerPolicy('1');

        $c['uploadConfig'] = function () {
            return [
                'path'          => __DIR__.'/../../cache',
                'client_upload' => '/claim_upload/',
            ];
        };

        $this->action = new \PP\Portal\Controller\Policy\ZipFiles($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testPolicyFile()
    {
        $action = $this->action;
        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, ['id' => 1]);

        $this->assertTrue($response->hasHeader('Content-Type'));
        $this->assertTrue($response->hasHeader('Content-Disposition'));
    }
}
