<?php

namespace PP\Test\Policy;

class FileDownloadTest extends \PHPUnit_Framework_TestCase
{
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        /*
        $c['pool'] = function () {
            $settings = [
                'path' => __DIR__.'/../../cache/data',
            ];
            $driver = new \Stash\Driver\FileSystem($settings);

            return new \Stash\Pool($driver);
        };*/
        $c['dataCacheConfig'] = ['expiresAfter' => 1];
        //$c['jsonConfig'] = ['prettyPrint' => false];
        /*
        $c['ViewHelper'] = function (\Slim\Container $c) {
            return new \PP\Portal\Module\Helper\View($c);
        };*/
        $c['ClaimFileModule'] = function ($c) {
            return new \PP\Portal\Module\ClaimFileModule($c);
        };
        /*
        $c['ClaimModule'] = function ($c) {
            return new \PP\Portal\Module\ClaimModule($c);
        };
        $c['ClaimModule']->geInfoById(1);
        */
        $c['uploadConfig'] = function () {
            return [
                'path' => __DIR__.'/../../cache',
            ];
        };

        $this->action = new \PP\Portal\Controller\Policy\FileDownload($c);
        $this->response = new \Slim\Http\Response();
    }

    /**
     * @expectedException \Slim\Exception\NotFoundException
     */
    public function testNotFound()
    {
        $action = $this->action;
        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, ['name'=>'aaa','id' => 10]);
    }

    public function testPolicyFile()
    {
        $action = $this->action;
        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, ['name'=>'policy-file','id' => 1]);

        $this->assertTrue($response->hasHeader('Content-Type'));
        $this->assertTrue($response->hasHeader('Content-Disposition'));
    }

    public function testPlanFile()
    {
        $action = $this->action;
        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, ['name'=>'plan-file','id' => 1]);

        $this->assertTrue($response->hasHeader('Content-Type'));
        $this->assertTrue($response->hasHeader('Content-Disposition'));
    }

}
