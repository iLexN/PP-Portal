<?php

namespace PP\Test\User;

class BankAccInfoTest extends \PHPUnit_Framework_TestCase
{
    protected $c;
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '3630' => [
                    'code'  => 3630,
                ],
                '3620' => [
                    'code'  => 3620,
                ],
            ];
        };
        $c['pool'] = function () {
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
            return new \PP\Portal\Module\UserModule($c);
        };
        $c['UserBankAccModule'] = function ($c) {
            return new \PP\Portal\Module\UserBankAccModule($c);
        };

        $this->c = $c;
        $this->action = new \PP\Portal\Controller\User\BankAccInfo($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testGet()
    {
        $this->c['UserModule']->isUserExistByID(2);
        $action = $this->action;
        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(3630, $out['status_code']);

        $this->assertArrayHasKey('banker_transfer_id', $out['data'][0]);
        $this->assertArrayHasKey('nick_name', $out['data'][0]);
        $this->assertArrayHasKey('currency', $out['data'][0]);
        $this->assertArrayHasKey('account_user_name', $out['data'][0]);
        $this->assertArrayHasKey('account_number', $out['data'][0]);
        $this->assertArrayHasKey('iban', $out['data'][0]);
        $this->assertArrayHasKey('branch_code', $out['data'][0]);
        $this->assertArrayHasKey('bank_swift_code', $out['data'][0]);
        $this->assertArrayHasKey('bank_name', $out['data'][0]);
        $this->assertArrayHasKey('additional_information', $out['data'][0]);
        $this->assertArrayHasKey('intermediary_bank_swift_code', $out['data'][0]);
        $this->assertArrayHasKey('ppmid', $out['data'][0]);
        $this->assertArrayHasKey('created_at', $out['data'][0]);
        $this->assertArrayHasKey('updated_at', $out['data'][0]);
    }

    public function testGetFail()
    {
        $this->c['UserModule']->isUserExistByID(173802);
        $action = $this->action;
        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(3620, $out['status_code']);
    }
}
