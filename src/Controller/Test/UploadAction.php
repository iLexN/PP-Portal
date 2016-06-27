<?php

namespace PP\Portal\Controller\Test;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UploadAction extends AbstractContainer
{
    /**
     * upload file action.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
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
     * @return \PP\Module\FileUploadModule
     */
    private function handerFile($file)
    {
        /* @var $newfile \PP\Module\FileUploadModule */
        $newfile = new \PP\Module\FileUploadModule($file);
        $newfile->setAllowFilesize('2M');
        $newfile->setAllowMimetype(['image/png', 'image/gif']);

        return $newfile;
    }
}
