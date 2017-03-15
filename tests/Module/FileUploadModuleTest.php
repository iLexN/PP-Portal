<?php

namespace PP\Test;

class FileUploadModuleTest extends \PHPUnit\Framework\TestCase
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
        $this->assertEmpty($uploadModule->getValidationMsg());
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
        $this->assertNotEmpty($uploadModule->getValidationMsg());
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
        $this->assertEmpty($uploadModule->getValidationMsg());
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
        $this->assertNotEmpty($uploadModule->getValidationMsg());
    }

    public function testUploadError()
    {
        $attr = [
            'tmp_name' => '.abc123',
            'name'     => 'my-avatar.txt',
            'size'     => 8,
            'type'     => 'text/plain',
            'error'    => 1,
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

        $this->assertFalse($uploadModule->isUploadSuccess());
        $this->assertNotEmpty($uploadModule->getValidationMsg());
    }

    public function testUploadErrorSuccess()
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

        $this->assertTrue($uploadModule->isUploadSuccess());
        $this->assertEmpty($uploadModule->getValidationMsg());
    }

    public function testUploadMove()
    {
        $uploadedFile = $this->getMockBuilder(\Slim\Http\UploadedFile::class)
                ->setMethods(['moveTo', 'getError'])
                ->disableOriginalConstructor()
                ->getMock();
        $uploadedFile->expects($this->once())
                ->method('moveTo')
                ->willReturn(true);

        $uploadModule = new \PP\Portal\Module\FileUploadModule($uploadedFile);

        $uploadModule->moveTo('a');
    }
}
