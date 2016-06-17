<?php

namespace PP\Test;

class TestFileUploadModule extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $attr = [
            'tmp_name' => '.abc123',
            'name'     => 'my-avatar.txt',
            'size'     => 8,
            'type'     => 'text/plain',
            'error'    => 0,
        ];
        $uploadedFile = new \Slim\Http\UploadedFile(
            $attr['tmp_name'],
            $attr['name'],
            $attr['type'],
            $attr['size'],
            $attr['error'],
            false
        );

        $uploadModule = new \PP\Portal\Module\FileUploadModule($uploadedFile);

        $this->assertEquals($attr['name'], $uploadModule->getClientFilename());
        //$this->assertEquals($attr['type'], $uploadModule->getClientMediaType());
        //$this->assertEquals($attr['size'], $uploadModule->getSize());
        $this->assertEquals($attr['error'], $uploadModule->getError());
    }

    public function testFilesizeSuccess()
    {
        $attr = [
            'tmp_name' => '.abc123',
            'name'     => 'my-avatar.txt',
            'size'     => 8,
            'type'     => 'text/plain',
            'error'    => 0,
        ];
        $uploadedFile = new \Slim\Http\UploadedFile(
            $attr['tmp_name'],
            $attr['name'],
            $attr['type'],
            $attr['size'],
            $attr['error'],
            false
        );

        $uploadModule = new \PP\Portal\Module\FileUploadModule($uploadedFile);

        $uploadModule->setAllowFilesize('2M');
        $this->assertTrue($uploadModule->isValid());
    }

    public function testFilesizeFail()
    {
        $attr = [
            'tmp_name' => '.abc123',
            'name'     => 'my-avatar.txt',
            'size'     => 800000000,
            'type'     => 'text/plain',
            'error'    => 0,
        ];
        $uploadedFile = new \Slim\Http\UploadedFile(
            $attr['tmp_name'],
            $attr['name'],
            $attr['type'],
            $attr['size'],
            $attr['error'],
            false
        );

        $uploadModule = new \PP\Portal\Module\FileUploadModule($uploadedFile);

        $uploadModule->setAllowFilesize('2M');
        $this->assertFalse($uploadModule->isValid());
    }

    public function testMimeTypeSuccess()
    {
        $attr = [
            'tmp_name' => '.abc123',
            'name'     => 'my-avatar.txt',
            'size'     => 8,
            'type'     => 'text/plain',
            'error'    => 0,
        ];
        $uploadedFile = new \Slim\Http\UploadedFile(
            $attr['tmp_name'],
            $attr['name'],
            $attr['type'],
            $attr['size'],
            $attr['error'],
            false
        );

        $uploadModule = new \PP\Portal\Module\FileUploadModule($uploadedFile);

        $uploadModule->setAllowMimetype(['text/plain', 'image/gif']);
        $this->assertTrue($uploadModule->isValid());
    }

    public function testMimeTypeFail()
    {
        $attr = [
            'tmp_name' => '.abc123',
            'name'     => 'my-avatar.txt',
            'size'     => 8,
            'type'     => 'text/plain',
            'error'    => 0,
        ];
        $uploadedFile = new \Slim\Http\UploadedFile(
            $attr['tmp_name'],
            $attr['name'],
            $attr['type'],
            $attr['size'],
            $attr['error'],
            false
        );

        $uploadModule = new \PP\Portal\Module\FileUploadModule($uploadedFile);

        $uploadModule->setAllowMimetype(['image/png', 'image/gif']);
        $this->assertFalse($uploadModule->isValid());
    }
}
