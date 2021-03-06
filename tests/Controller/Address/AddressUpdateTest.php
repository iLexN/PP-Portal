<?php

namespace PP\Test\Address;

class AddressUpdateTest extends \PHPUnit\Framework\TestCase
{
    protected $action;
    protected $response;
    protected $request;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '1020' => [
                    'code'  => 1020,
                ],
                '5060' => [
                    'code'  => 5060,
                ],
                '2620' => [
                    'code'  => 2620,
                ],
                '2626' => [
                    'code'  => 2626,
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
        $c['UserPolicyModule'] = function ($c) {
            $userPolicyModule = new \PP\Portal\Module\UserPolicyModule($c);
            $userPolicyModule->getUerPolicy(1);

            return $userPolicyModule;
        };

        $this->action = new \PP\Portal\Controller\Address\AddressUpdate($c);
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

        $response = $action($request, $response, ['id' => 2, 'acid' => '1111', 'mode' => 'User']);
    }

    public function testMissingData()
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

        $response = $action($request, $response, ['id' => 2, 'acid' => '1', 'mode' => 'User']);
        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(1020, $out['status_code']);
    }

    public function testUserAddressSuccess()
    {
        $action = $this->action;

        $_POST['nick_name'] = 'alex';
        $_POST['address_line_2'] = 'line2';
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, ['id' => 2, 'acid' => '1', 'mode' => 'User']);
        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2620, $out['status_code']);

        $this->assertArrayHasKey('nick_name', $out['data']);
        $this->assertArrayHasKey('address_line_2', $out['data']);
        $this->assertArrayHasKey('address_line_3', $out['data']);
        $this->assertArrayHasKey('address_line_4', $out['data']);
        $this->assertArrayHasKey('address_line_5', $out['data']);
        $this->assertArrayHasKey('ppmid', $out['data']);
        $this->assertArrayHasKey('status', $out['data']);
        $this->assertEquals('Active', $out['data']['status']);
        $this->assertArrayHasKey('old_id', $out['data']);
        $this->assertArrayHasKey('id', $out['data']);
    }

    public function testUserAddressNickNameSame()
    {
        $action = $this->action;

        $_POST['nick_name'] = 'alex';
        $_POST['address_line_2'] = 'line4';
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, ['id' => 2, 'acid' => '1', 'mode' => 'User']);
        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2620, $out['status_code']);
    }

    public function testUserAddressNickNameUsed()
    {
        $action = $this->action;

        $_POST['nick_name'] = 'test';
        $_POST['address_line_2'] = 'line4';
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, ['id' => 2, 'acid' => '1', 'mode' => 'User']);
        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2626, $out['status_code']);
    }
}
