<?php

namespace PP\Test;

class ClaimFileModuleTest extends \PHPUnit_Framework_TestCase
{
    protected $ClaimFileModule;
    protected $FileUploadModule;

    protected function setUp()
    {
        $c = new \Slim\Container();

        $c['pool'] = function ($c) {
            $settings = [
                'path' => __DIR__.'/../cache/data',
            ];
            $driver = new \Stash\Driver\FileSystem($settings);

            return new \Stash\Pool($driver);
        };
        $c['uploadConfig'] = function () {
            return [
                'path' => __DIR__.'/../cache',
            ];
        };

        $c['dataCacheConfig'] = ['expiresAfter' => 1];

        $c['ClaimModule'] = function ($c) {
            return new \PP\Portal\Module\ClaimModule($c);
        };
        $c['ClaimModule']->geInfoById(1);



        $file = $this->getMockBuilder(\Slim\Http\UploadedFile::class)
                ->setMethods(['moveTo', 'getClientFilename'])
                ->disableOriginalConstructor()
                ->getMock();
        $file->method('moveTo')->willReturn(true);
        $file->method('getClientFilename')->willReturn('a.txt');

        $uploadModule = new \PP\Portal\Module\FileUploadModule($file);
        $this->FileUploadModule = $uploadModule;

        $ClaimFileModule = new \PP\Portal\Module\ClaimFileModule($c);
        $this->ClaimFileModule = $ClaimFileModule;
    }

    public function testNewClaimFile()
    {
        $this->ClaimFileModule->newClaimFile($this->FileUploadModule);
        $this->expectOutputString('foo');
        echo 'foo';
    }

    public function testGetFile()
    {
        $file = $this->ClaimFileModule->getFile(1);
        $this->assertInstanceOf(\PP\Portal\DbModel\ClaimFile::class, $file);

        return $this->ClaimFileModule;
    }

    /**
     * @depends testGetFile
     */
    public function testDeleteFile($ClaimFileModule)
    {
        $ClaimFileModule->deleteFile();
        $this->expectOutputString('foo');
        echo 'foo';
    }

    /**
     * @depends testGetFile
     */
    public function testGetFilePath($ClaimFileModule)
    {
        $this->assertNotEmpty($ClaimFileModule->getFilePath());

        $ClaimFileModule->file->filename = 'b.txt';
        $this->assertFalse($ClaimFileModule->getFilePath());
    }
}
