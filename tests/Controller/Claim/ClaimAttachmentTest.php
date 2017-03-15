<?php

namespace PP\Test\Claim;

class ClaimAttachmentTest extends \PHPUnit_Framework_TestCase
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
                '1810' => [
                    'code'  => 1810,
                ],
                '1820' => [
                    'code'  => 1820,
                ],
                '1830' => [
                    'code'  => 1830,
                ],
                '1840' => [
                    'code'  => 1840,
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
        $c['uploadConfig'] = function () {
            return [
                'path' => __DIR__.'/../../cache',
                'client_upload' => '/claim_upload/',
            ];
        };
        $c['ClaimFileModule'] = function ($c) {
            return new \PP\Portal\Module\ClaimFileModule($c);
        };
        $c['ClaimModule'] = function ($c) {
            return new \PP\Portal\Module\ClaimModule($c);
        };
        $c['ClaimModule']->geInfoById(1);

        $this->action = new \PP\Portal\Controller\Claim\ClaimAttachment($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testNoFileType()
    {
        $action = $this->action;

        $_POST = [];
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

    public function testNoFile()
    {
        $action = $this->action;

        $_POST['file_type'] = 'support_doc';
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(1810, $out['status_code']);
    }

    public function testUploadError()
    {
        $action = $this->action;
        $request = $this->getMockBuilder(\Slim\Http\Request::class)
                ->setMethods(['getUploadedFiles', 'getParsedBody'])
                ->disableOriginalConstructor()
                ->getMock();
        $file = $this->getMockBuilder(\Slim\Http\UploadedFile::class)
                ->setMethods(['moveTo', 'getClientFilename', 'getError'])
                ->disableOriginalConstructor()
                ->getMock();
        $file->method('getError')->willReturn('a');
        $request->method('getUploadedFiles')->willReturn(['newfile' => $file]);
        $request->method('getParsedBody')->willReturn(['file_type' => 'support_doc']);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(1830, $out['status_code']);
    }

    public function testInValid()
    {
        $action = $this->action;
        $request = $this->getMockBuilder(\Slim\Http\Request::class)
                ->setMethods(['getUploadedFiles', 'getParsedBody'])
                ->disableOriginalConstructor()
                ->getMock();
        $file = $this->getMockBuilder(\Slim\Http\UploadedFile::class)
                ->setMethods(['moveTo', 'getClientMediaType', 'getError'])
                ->disableOriginalConstructor()
                ->getMock();
        $file->method('getError')->willReturn(UPLOAD_ERR_OK);
        $file->method('getClientMediaType')->willReturn('aaa');
        $request->method('getUploadedFiles')->willReturn(['newfile' => $file]);
        $request->method('getParsedBody')->willReturn(['file_type' => 'support_doc']);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(1820, $out['status_code']);
    }

    public function testSuccess()
    {
        $action = $this->action;
        $request = $this->getMockBuilder(\Slim\Http\Request::class)
                ->setMethods(['getUploadedFiles', 'getParsedBody'])
                ->disableOriginalConstructor()
                ->getMock();
        $file = $this->getMockBuilder(\Slim\Http\UploadedFile::class)
                ->setMethods(['moveTo', 'getClientMediaType', 'getError', 'getClientFilename'])
                ->disableOriginalConstructor()
                ->getMock();
        $file->method('getError')->willReturn(UPLOAD_ERR_OK);
        $file->method('getClientMediaType')->willReturn('image/jpeg');
        $file->method('moveTo')->willReturn(true);
        $file->method('getClientFilename')->willReturn('upload.png');
        $request->method('getUploadedFiles')->willReturn(['newfile' => $file]);
        $request->method('getParsedBody')->willReturn(['file_type' => 'support_doc']);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(1840, $out['status_code']);
    }
}
