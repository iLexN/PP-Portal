<?php

namespace PP\Portal\Controller\Policy;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\PlanFile;
use PP\Portal\DbModel\PolicyFile;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class FileDownload extends AbstractContainer
{
    private $fileInfo = [];

    private $fileObj;

    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $this->fileObj = $this->getFileData($args);

        if (!$this->fileObj || !file_exists($this->getFilePath())) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }
        $path = $this->getFilePath();

        $stream = fopen($path, 'r');

        return $response
                ->withBody(new \Slim\Http\Stream($stream))
                ->withHeader('Content-Type', mime_content_type($path))
                ->withHeader('Content-Disposition', 'attachment; filename="'.$this->fileObj->file_name.'"');
    }

    private function getFileData($info)
    {
        if ($info['name'] == 'policy-file') {
            $this->fileInfo = [
                'folder' => 'policy_documents',
                'idKey'  => 'ppib',
            ];

            return PolicyFile::find($info['id']);
        } else {
            //plan-file
            $this->fileInfo = [
                'folder' => 'plan_documents',
                'idKey'  => 'plan_id',
            ];
            return PlanFile::find($info['id']);
        }
    }

    private function getFilePath()
    {
        return $this->c->get('uploadConfig')['path'].'/'.
                $this->fileInfo['folder'].'/'.$this->fileObj->{$this->fileInfo['idKey']}.'/'.
                $this->fileObj['file_type'].'/'.$this->fileObj->file_name;
    }
}
