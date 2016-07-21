<?php

namespace PP\Portal\Module;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\ClaimFile;
use \PP\Portal\Module\FileUploadModule;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

class ClaimFileModule extends AbstractContainer
{
    public function newClaimFile(FileUploadModule $newfile){
        $file = new ClaimFile();
        $file->claim_id = $this->ClaimModule->claim->claim_id;
        $file->filename = $newfile->getClientFilename();
        $file->save();

        $id = $file->id;
        $this->moveFiles($id, $newfile);
    }

    private function moveFiles($id,FileUploadModule $newfile){
        $adapter = new Local($this->c->get('uploadConfig')['path']);
        $filesystem = new Filesystem($adapter);
        $dirPath = $this->ClaimModule->claim->claim_id . '/' . $id;
        $filesystem->createDir($dirPath);
        $newfile->moveTo($this->c->get('uploadConfig')['path'].'/'.$dirPath.'/'.$newfile->getClientFilename());
    }
}
