<?php

namespace PP\Portal\Controller\Policy;

use Genkgo\ArchiveStream\Archive;
use Genkgo\ArchiveStream\FileContent;
use Genkgo\ArchiveStream\Psr7Stream;
use Genkgo\ArchiveStream\ZipReader;
use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class ZipFiles extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        /* @var $policy \PP\Portal\DbModel\UserPolicy */
        $policy = $this->UserPolicyModule->userPolicy;
        $plansFiles = $policy->policyPlanFile()->get();
        $policyFiles = $policy->policyFiles()->get();

        $outFilesArray = $this->getFilesList($policyFiles, $plansFiles);

        $archive = (new Archive());

        foreach ($outFilesArray as $fInfo) {
            $archive = $archive->withContent(new FileContent($fInfo['filename'], $this->c->get('uploadConfig')['path'].'/'.$fInfo['path']));
        }

        return  $response->withBody(new Psr7Stream(new ZipReader($archive)))
                ->withHeader('Content-Type', 'application/zip')
                ->withHeader('Content-Disposition', 'attachment; filename="Important-Forms.zip"');
    }

    private function getFilesList($policyFiles, $plansFiles)
    {
        $outFilesArray = [];
        foreach ($policyFiles as $pF) {
            $outFilesArray[] = ['path'=>$pF->getFilePath(), 'filename'=>$pF->file_name];
        }
        foreach ($plansFiles as $pF) {
            $outFilesArray[] = ['path'=>$pF->getFilePath(), 'filename'=>$pF->file_name];
        }

        return $outFilesArray;
    }
}
