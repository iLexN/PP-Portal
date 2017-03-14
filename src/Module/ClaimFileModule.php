<?php

namespace PP\Portal\Module;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\ClaimFile;

class ClaimFileModule extends AbstractContainer
{
    /**
     * @var \PP\Portal\DbModel\ClaimFile
     */
    public $file;

    public function newClaimFile(FileUploadModule $newfile, $data)
    {
        $file = new ClaimFile();
        $file->claim_id = $this->ClaimModule->claim->claim_id;
        $file->file_type = $data['file_type'];
        $file->status = 'Upload';
        $file->filename = $newfile->getClientFilename();
        $file->save();

        $id = $file->id;
        $this->moveFiles($id, $newfile);
        $this->ClaimModule->clearCache();

        return $file;
    }

    /**
     * @param int              $id
     * @param FileUploadModule $newfile
     */
    private function moveFiles($id, FileUploadModule $newfile)
    {
        $filesystem = $this->getFileSystem();
        $dirPath = $this->ClaimModule->claim->claim_id.'/'.$id;
        $filesystem->createDir($dirPath);
        $newfile->moveTo($this->c->get('uploadConfig')['path'].$this->c->get('uploadConfig')['client_upload'].$dirPath.'/'.$newfile->getClientFilename());
    }

    public function getFile($id)
    {
        $this->file = ClaimFile::find($id);

        return $this->file;
    }

    public function deleteFile()
    {
        //$this->removeFile($path);
        $this->statusUpdate();
    }

    private function statusUpdate()
    {
        $this->file->status = 'Delete';
        $this->file->save();
        $this->ClaimModule->geInfoById($this->file->claim_id);
        $this->ClaimModule->clearCache();
    }

    /*
    private function removeFile($path){
        $filesystem = $this->getFileSystem();
        $filesystem->delete($path);

    }*/

    public function getFilePath()
    {
        $filePath = $this->c->get('uploadConfig')['path'].$this->c->get('uploadConfig')['client_upload'].
                $this->file->claim_id.'/'.
                $this->file->id.'/'.
                $this->file->filename;

        if (file_exists($filePath)) {
            return $filePath;
        } else {
            return false;
        }
    }

    private function getFileSystem()
    {
        $adapter = new Local($this->c->get('uploadConfig')['path'].$this->c->get('uploadConfig')['client_upload']);
        $filesystem = new Filesystem($adapter);

        return $filesystem;
    }
}
