<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

class ClaimAttachment extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {

        $files = $request->getUploadedFiles();

        if (empty($files['newfile'])) {
            return $this->ViewHelper->toJson($response, [
                    'errors' => $this->msgCode['1810'],
                ]);
        }

        $newfile = $this->handerFile($files['newfile']);

        if ( !$newfile->isUploadSuccess() ) {
            return $this->ViewHelper->withStatusCode($response, [
                'errors' => $newfile->getValidationMsg(),
            ],1830);
        }

        if (!$newfile->isValid()) {
            return $this->ViewHelper->withStatusCode($response, [
                //'errors' => $newfile->getValidationMsg(),
                'errors' => $this->msgCode['1820'],
            ],1820);
        }

        $this->ClaimFileModule->newClaimFile($newfile);

        return $this->ViewHelper->withStatusCode($response, [
                'data' => [
                    'filename' => $newfile->getClientFilename(),
                ],
            ],1840);
        
    }

    /**
     * @param \Slim\Http\UploadedFile $file
     *
     * @return \PP\Portal\Module\FileUploadModule
     */
    private function handerFile($file)
    {
        /* @var $newfile \PP\Portal\Module\FileUploadModule */
        $newfile = new \PP\Portal\Module\FileUploadModule($file);
        $newfile->setAllowFilesize('2M');
        $newfile->setAllowMimetype(['image/png', 'image/jpeg','application/pdf']);

        return $newfile;
    }
}
