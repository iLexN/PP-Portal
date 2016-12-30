<?php

namespace PP\Test\Address;

class AddressDeleteTest extends \PHPUnit_Framework_TestCase
{
    protected $action;
    protected $response;
    protected $request;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '2625' => [
                    'code'  => 2625,
                ],
            ];
        };

        $c['pool'] = function ($c) {
            $settings = [
                'path' => __DIR__.'/../../cache/data',
            ];
            $driver = new \Stash\Driver\FileSystem($settings);

            return new \Stash\Pool($driver);
        };

        $c['dataCacheConfig'] = ['expiresAfter' => 1];

        $c['jsonConfig'] = ['prettyPrint' => false];
        $c['ViewHelper'] = function (\Slim\Container $c) {
            return new \PP\Portal\Module\Helper\View($c);
        };

        $c['UserModule'] = function ($c) {
            $userModule = new \PP\Portal\Module\UserModule($c);
            $userModule->isUserExistByID(2);

            return $userModule;
        };
        $c['AddressModule'] = function ($c) {
            return new \PP\Portal\Module\AddressModule($c);
        };

        $this->action = new \PP\Portal\Controller\Address\AddressDelete($c);
        $this->response = new \Slim\Http\Response();
    }

    /**
     * @expectedException \Slim\Exception\NotFoundException
     */
    public function testNotFound()
    {
        $action = $this->action;

        $_POST['email'] = 'alex';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, ['id' => 2, 'acid' => '1111']);
    }

    public function testUserAddressDelete()
    {
        $action = $this->action;

        $_POST['nick_name'] = 'alex';
        $environment = \Slim\Http\Environment::mock([
            //'REQUEST_METHOD'    => 'Delete',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;

        $response = $action($request, $response, ['id' => 2, 'acid' => '3']);
        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2625, $out['status_code']);
    }
}
