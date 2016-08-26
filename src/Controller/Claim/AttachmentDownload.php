<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class AttachmentDownload extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $file = $this->ClaimFileModule->getFile($args['id']);

        if (!$file || $file->status === 'Delete') {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $path = $this->ClaimFileModule->getFilePath();
        if ($path === false) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $stream = fopen($path, 'r');

        return $response
                ->withBody(new \Slim\Http\Stream($stream))
                ->withHeader('Content-Type', mime_content_type($path))
                ->withHeader('Content-Disposition', 'attachment; filename="'.$file->filename.'"');
    }
}
