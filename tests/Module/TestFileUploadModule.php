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
}
