<?php

namespace PP\Portal\Module;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\ClaimFile;
use PP\Portal\Module\FileUploadModule;

class ClaimFileModule extends AbstractContainer
{
    /**
     * @var \PP\Portal\DbModel\ClaimFile
     */
    public $file;

    public function newClaimFile(FileUploadModule $newfile)
    {
        $file = new ClaimFile();
        $file->claim_id = $this->ClaimModule->claim->claim_id;
        $file->filename = $newfile->getClientFilename();
        $file->save();

        $id = $file->id;
        $this->moveFiles($id, $newfile);
        $this->ClaimModule->clearCache();
    }

    /**
     *
     * @param int $id
     * @param FileUploadModule $newfile
     */
    private function moveFiles($id, FileUploadModule $newfile)
    {
        $adapter = new Local($this->c->get('uploadConfig')['path']);
        $filesystem = new Filesystem($adapter);
        $dirPath = $this->ClaimModule->claim->claim_id.'/'.$id;
        $filesystem->createDir($dirPath);
        $newfile->moveTo($this->c->get('uploadConfig')['path'].'/'.$dirPath.'/'.$newfile->getClientFilename());
    }

    public function getFile($id)
    {
        $this->file = ClaimFile::find($id);

        return $this->file;
    }

    public function getFilePath()
    {
        $filePath = $this->c->get('uploadConfig')['path'].'/'.
                $this->file->claim_id.'/'.
                $this->file->id.'/'.
                $this->file->filename;

        if (file_exists($filePath)) {
            return $filePath;
        } else {
            return false;
        }
    }
}
