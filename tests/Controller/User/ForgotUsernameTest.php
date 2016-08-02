<?php

namespace PP\Test\User;

class ForgotUsername extends \PHPUnit_Framework_TestCase
{
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
                '2010' => [
                    'code'  => 2010,
                ],
                '2550' => [
                    'code'  => 2550,
                ],
            ];
        };
        $c['pool'] = function () {
            $settings = [
                'path' => __DIR__.'/../cache/data',
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
        $this->action = new \PP\Portal\Controller\User\ForgotUsername($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testValidator()
    {
        $action = $this->action;

        $_POST['name'] = 'alex';
        $_POST['email'] = '123Psadfs';
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

    public function testIsUser()
    {
        $action = $this->action;

        $_POST['name'] = 'alex';
        $_POST['email'] = 'a@a.com';
        $_POST['phone'] = '1';
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

     public function testSave()
    {
        $action = $this->action;

        $_POST['name'] = 'alex';
        $_POST['email'] = 'alex@kwiksure.com';
        $_POST['phone'] = '12345678';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2550, $out['status_code']);
    }


}
