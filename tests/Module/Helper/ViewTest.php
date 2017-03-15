<?php

namespace PP\Test;

class ViewTest extends \PHPUnit\Framework\TestCase
{
    protected $c;

    protected function setUp()
    {
        $c = new \Slim\Container();

        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '1530' => [
                    'code'  => 1530,
                ],
                '123' => [
                    'code'  => 123,
                ],
            ];
        };
        $this->c = $c;
    }

    public function testJsonNormal()
    {
        $this->c['jsonConfig'] = ['prettyPrint' => false];

        $view = new \PP\Portal\Module\Helper\View($this->c);

        $response = new \Slim\Http\Response();

        $rs = ['abc' => 1];

        $response = $view->toJson($response, $rs);

        $rs['status_code'] = 1530;

        $this->assertJsonStringEqualsJsonString(
            json_encode($rs, JSON_UNESCAPED_SLASHES),
            (string) $response->getBody()
        );

        $this->assertTrue($response->hasHeader('Content-type'));
        $this->assertEquals('application/json', $response->getHeaderLine('Content-type'));
    }

    public function testJsonPretty()
    {
        $this->c['jsonConfig'] = ['prettyPrint' => true];

        $view = new \PP\Portal\Module\Helper\View($this->c);

        $response = new \Slim\Http\Response();

        $rs = ['abc' => 1];

        $response = $view->toJson($response, $rs);

        $rs['status_code'] = 1530;

        $this->assertJsonStringEqualsJsonString(
            json_encode($rs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            (string) $response->getBody()
        );

        $this->assertTrue($response->hasHeader('Content-type'));
        $this->assertEquals('application/json', $response->getHeaderLine('Content-type'));
    }

    public function testDataCode()
    {
        $this->c['jsonConfig'] = ['prettyPrint' => true];

        $view = new \PP\Portal\Module\Helper\View($this->c);

        $response = new \Slim\Http\Response();

        $rs = ['data' => ['code' => 123]];

        $response = $view->toJson($response, $rs);

        $out = json_decode((string) $response->getBody(), true);

        $this->assertEquals(123, $out['status_code']);
    }

    public function testErrorCode()
    {
        $this->c['jsonConfig'] = ['prettyPrint' => true];

        $view = new \PP\Portal\Module\Helper\View($this->c);

        $response = new \Slim\Http\Response();

        $rs = ['errors' => ['code' => 123]];

        $response = $view->toJson($response, $rs);

        $out = json_decode((string) $response->getBody(), true);

        $this->assertEquals(123, $out['status_code']);
    }

    public function testWithStatusCode()
    {
        $this->c['jsonConfig'] = ['prettyPrint' => true];

        $view = new \PP\Portal\Module\Helper\View($this->c);

        $response = new \Slim\Http\Response();

        $rs = ['abc' => 1];

        $response = $view->withStatusCode($response, $rs, 123);

        $out = json_decode((string) $response->getBody(), true);

        $this->assertEquals(123, $out['status_code']);
    }
}
