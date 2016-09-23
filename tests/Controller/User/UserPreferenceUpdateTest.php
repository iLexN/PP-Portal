<?php

namespace PP\Test\User;

class UserPreferenceUpdateTest extends \PHPUnit_Framework_TestCase
{
    protected $c;
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '1020' => [
                    'code'  => 1020,
                ],
                '3650' => [
                    'code'  => 3650,
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
        $c['UserPreferenceModule'] = function ($c) {
            return new \PP\Portal\Module\UserPreferenceModule($c);
        };

        $this->c = $c;
        $this->action = new \PP\Portal\Controller\User\UserPreferenceUpdate($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testSuccess()
    {
        $this->c['UserModule']->isUserExistByID(2);

        $action = $this->action;

        $_POST = [
            'currency'         => 'HKD',
            'currency_receive' => 'HKD',
        ];
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(3650, $out['status_code']);
    }

    public function testFail()
    {
        $this->c['UserModule']->isUserExistByID(2);

        $action = $this->action;

        $_POST = [
            'currency-wrong'   => 'HKD',
            'currency_receive' => 'HKD',
        ];
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(1020, $out['status_code']);
    }

    /**
     * @expectedException \Slim\Exception\NotFoundException
     */
    public function testNotFound()
    {
        $this->c['UserModule']->isUserExistByID(9677);

        $action = $this->action;

        $_POST = [
            'currency-wrong'   => 'HKD',
            'currency_receive' => 'HKD',
        ];
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;
        $response = $action($request, $response, []);
    }
}
