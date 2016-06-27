<?php

namespace PP\Test;

class ForgotPasswordTest extends \PHPUnit_Framework_TestCase
{
    public function testUserNotFind()
    {
        $c = $this->setUpContainer();

        $c['UserModule'] = function () {
            $userModule = $this->getMockBuilder(UserModule::class)
                ->setMethods(['isUserExistByID'])
                ->disableOriginalConstructor()
                ->getMock();
            $userModule->expects($this->once())
                ->method('isUserExistByID')
                ->willReturn(false);

            return $userModule;
        };

        $action = new \PP\Portal\Controller\User\ForgotPassword($c);

        $_POST = ['clientID' => '1'];
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = new \Slim\Http\Response();

        $response = $action($request, $response, []);

        $this->assertJsonStringEqualsJsonString(
            json_encode(['errors' => [
                'title' => 'Login User Not Found',
            ]]),
            json_encode(json_decode((string) $response->getBody()))
        );
    }

    public function testMissFields()
    {
        $c = $this->setUpContainer();

        $c['UserModule'] = function () {
            $userModule = $this->getMockBuilder(UserModule::class)
                ->setMethods(['isUserExistByID'])
                ->disableOriginalConstructor()
                ->getMock();
            $userModule->expects($this->once())
                ->method('isUserExistByID')
                ->willReturn(false);

            return $userModule;
        };

        $action = new \PP\Portal\Controller\User\ForgotPassword($c);

        $_POST = ['a' => '1'];
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = new \Slim\Http\Response();

        $response = $action($request, $response, []);

        $this->assertJsonStringEqualsJsonString(
            json_encode(['errors' => [
                'title' => 'Missing field(s)',
            ]]),
            json_encode(json_decode((string) $response->getBody()))
        );
    }

    public function testSend()
    {
        $c = $this->setUpContainer();

        $c['UserModule'] = function () {
            $userModule = $this->getMockBuilder(UserModule::class)
                ->setMethods(['isUserExistByID'])
                ->disableOriginalConstructor()
                ->getMock();
            $userModule->expects($this->once())
                ->method('isUserExistByID')
                ->willReturn(true);
            $client = $this->getMockBuilder(\PP\Portal\DbModel\Client::class)
                    ->setMethods(['First_Name','Surname']);
            $client->First_Name = 'First_Name';
            $client->Surname = 'Surname';
            $userModule->client = $client;

            return $userModule;
        };
        $c['mailer'] = function () {
            $mailer = $this->getMockBuilder(PHPMailer::class)
                ->setMethods(['send','setFrom','addAddress','Subject','msgHTML'])
                ->disableOriginalConstructor()
                ->getMock();
            $mailer->expects($this->once())
                ->method('send')
                ->willReturn(true);

            return $mailer;
        };

        $c['logger'] = function () {
            $logger = $this->getMockBuilder(\Monolog\Logger::class)
                    ->setMethods(['error','info'])
                    ->disableOriginalConstructor()
                    ->getMock();
            $logger->expects($this->once())
                ->method('info');

            return $logger;
        };

        $action = new \PP\Portal\Controller\User\ForgotPassword($c);

        $_POST = ['clientID' => '1'];
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = new \Slim\Http\Response();

        $response = $action($request, $response, []);

        $this->assertJsonStringEqualsJsonString(
            json_encode(['data' => [
                'title' => true,
            ]]),
            json_encode(json_decode((string) $response->getBody()))
        );
    }

    public function testSendFail()
    {
        $c = $this->setUpContainer();

        $c['UserModule'] = function () {
            $userModule = $this->getMockBuilder(UserModule::class)
                ->setMethods(['isUserExistByID'])
                ->disableOriginalConstructor()
                ->getMock();
            $userModule->expects($this->once())
                ->method('isUserExistByID')
                ->willReturn(true);
            $client = $this->getMockBuilder(\PP\Portal\DbModel\Client::class)
                    ->setMethods(['First_Name','Surname']);
            $client->First_Name = 'First_Name';
            $client->Surname = 'Surname';
            $userModule->client = $client;

            return $userModule;
        };
        $c['mailer'] = function () {
            $mailer = $this->getMockBuilder(PHPMailer::class)
                ->setMethods(['send','setFrom','addAddress','Subject','msgHTML'])
                ->disableOriginalConstructor()
                ->getMock();
            $mailer->expects($this->once())
                ->method('send')
                ->willReturn(false);

            return $mailer;
        };

        $c['logger'] = function () {
            $logger = $this->getMockBuilder(\Monolog\Logger::class)
                    ->setMethods(['error','info'])
                    ->disableOriginalConstructor()
                    ->getMock();
            $logger->expects($this->once())
                ->method('error');

            return $logger;
        };

        $action = new \PP\Portal\Controller\User\ForgotPassword($c);

        $_POST = ['clientID' => '1'];
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = new \Slim\Http\Response();

        $response = $action($request, $response, []);

        $this->assertJsonStringEqualsJsonString(
            json_encode(['data' => [
                'title' => true,
            ]]),
            json_encode(json_decode((string) $response->getBody()))
        );
    }

    public function setUpContainer()
    {
        $c = new \Slim\Container();

        $c['jsonConfig'] = ['prettyPrint' => false];

        $c['ViewHelper'] = function ($c) {
            return new \PP\Portal\Module\Helper\View($c);
        };

        $c['pool'] = function () {
            $settings = [
                'path' => __DIR__.'/../cache/data',
            ];
            $driver = new \Stash\Driver\FileSystem($settings);

            return new \Stash\Pool($driver);
        };

        $c['dataCacheConfig'] = ['expiresAfter' => 3600];

        $c['twigView'] = function(){
           $twig = $this->getMockBuilder(\Slim\Views\Twig::class)
                ->setMethods(['fetch'])
                ->disableOriginalConstructor()
                ->getMock();
           return $twig;
        };
        
        return $c;
    }
}
