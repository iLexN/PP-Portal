<?php

namespace PP\Portal\Controller\Test;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \League\Fractal\Resource\Collection;

class UploadAction
{
    /**
     * @var \Slim\Container
     */
    protected $c;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

    /**
     * Email Auth Check action.
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

        if (empty($files['newfile'])) {
            throw new \Exception('Expected a newfile');
        }

        $newfile = $this->handerFile($files['newfile']);

        if ($newfile->isValid()) {
            $newfile->moveTo($this->c->get('uploadConfig')['path'].'/'.$newfile->getClientFilename());

            return $this->c['view']->render($request, $response, [
                'data'=>[
                    'filename' => $newfile->getClientFilename(),
                ]
            ]);
        }
        //var_dump($newfile->getValidationMsg());
        
        return $this->c['view']->render($request, $response, [
                'errors'=>$newfile->getValidationMsg(),
            ]);
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
