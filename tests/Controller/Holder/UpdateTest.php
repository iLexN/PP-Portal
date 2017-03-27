<?php

namespace PP\Test\Holder;

class UpdateTest extends \PHPUnit\Framework\TestCase
{
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '2641' => [
                    'code'  => 2641,
                ],
                '1020' => [
                    'code'  => 1020,
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

        $this->action = new \PP\Portal\Controller\Holder\Update($c);
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
        $response = $action($request, $response, ['id' => 10000]);
    }

    public function testPost()
    {
        $action = $this->action;

        $_POST['mail_address_line_2'] = '1111';
        $_POST['policy_address_line_2'] = '2222';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;
        $response = $action($request, $response, ['id' => 1]);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2641, $out['status_code']);
        $this->assertEquals('Pending', $out['data']['status']);
    }

    public function testMissInfo()
    {
        $action = $this->action;

        $_POST['mail_address_line_2'] = '1111';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;
        $response = $action($request, $response, ['id' => 1]);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(1020, $out['status_code']);
    }
}
