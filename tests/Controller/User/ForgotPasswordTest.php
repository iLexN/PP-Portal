<?php

namespace PP\Test\User;

class ForgotPasswordTest extends \PHPUnit_Framework_TestCase
{
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '1010' => [
                    'code'  => 1010,
                ],
                '2540' => [
                    'code'  => 2540,
                ],
                '2010' => [
                    'code'  => 2010,
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
        // mail
        $c['mailer'] = function ($c){
            $m = $this->getMockBuilder(\PHPMailer::class)
                ->setMethods(['setFrom','addAddress','Subject','msgHTML','send'])
                ->disableOriginalConstructor()
                ->getMock();
            $m->method('send')->willReturn(true);
            return $m;
        };
        $c['mailConfig'] = function ($c){
            return [
                'fromAc'=>'dd',
                'fromName'=>'dd',
            ];
        };
        $c['twigView'] = function ($c) {
            $m = $this->getMockBuilder(\Slim\Views::class)
                ->setMethods(['fetch'])
                ->disableOriginalConstructor()
                ->getMock();
            return $m;
        };
        // end mail
        $this->action = new \PP\Portal\Controller\User\ForgotPassword($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testValidator()
    {
        $action = $this->action;

        $_POST['name'] = 'alex';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(1010, $out['status_code']);
    }

    public function testIsUserExistByUsername()
    {
        $action = $this->action;

        $_POST['user_name'] = 'alex';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2540, $out['status_code']);
    }

    public function testUserNotFound()
    {
        $action = $this->action;

        $_POST['user_name'] = 'alddex';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2010, $out['status_code']);
    }
}
