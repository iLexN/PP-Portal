<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class AttachmentDel extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $file = $this->ClaimFileModule->getFile($args['id']);

        if (!$file) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        //$path = $this->ClaimFileModule->getFilePath();

        // really del ?
        //$this->ClaimFileModule->deleteFile($path);
        $this->ClaimFileModule->deleteFile();

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $this->msgCode[1850],
                ], 1850);
    }
}
