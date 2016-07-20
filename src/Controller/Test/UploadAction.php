<?php

namespace PP\Portal\Controller\Test;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

class UploadAction extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $files = $request->getUploadedFiles();

        if (!empty($files['newfile'])) {
            $newfile = $this->handerFile($files['newfile']);

            if ($newfile->isValid()) {
                $newfile->moveTo($this->c->get('uploadConfig')['path'].'/'.$newfile->getClientFilename());

                return $this->c['ViewHelper']->toJson($response, [
                    'data' => [
                        'filename' => $newfile->getClientFilename(),
                    ],
                ]);
            }

            return $this->c['ViewHelper']->toJson($response, [
                    'errors' => $newfile->getValidationMsg(),
                ]);
        }
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
        $newfile->setAllowMimetype(['image/png', 'image/gif']);

        return $newfile;
    }
}
