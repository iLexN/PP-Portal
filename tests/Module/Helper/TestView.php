<?php

namespace PP\Test;

class TestView extends \PHPUnit_Framework_TestCase
{
    private $c;

    public function testJsonNormal()
    {
        $c = $this->setUpContainer();

        $c['jsonConfig'] = ['prettyPrint' => false];

        $view = new \PP\Portal\Module\Helper\View($c);

        $response = new \Slim\Http\Response();

        $rs = ['abc' => 1];

        $response = $view->toJson($response, $rs);

        $this->assertJsonStringEqualsJsonString(
            json_encode($rs, JSON_UNESCAPED_SLASHES),
            (string) $response->getBody()
        );

        $this->assertTrue($response->hasHeader('Content-type'));
        $this->assertEquals('application/json', $response->getHeaderLine('Content-type'));
    }

    public function testJsonPretty()
    {
        $c = $this->setUpContainer();

        $c['jsonConfig'] = ['prettyPrint' => true];

        $view = new \PP\Portal\Module\Helper\View($c);

        $response = new \Slim\Http\Response();

        $rs = ['abc' => 1];

        $response = $view->toJson($response, $rs);

        $this->assertJsonStringEqualsJsonString(
            json_encode($rs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            (string) $response->getBody()
        );

        $this->assertTrue($response->hasHeader('Content-type'));
        $this->assertEquals('application/json', $response->getHeaderLine('Content-type'));
    }

    public function setUpContainer()
    {
        $app = new \Slim\App();
        $c = $app->getContainer();

        return $c;
    }
}
