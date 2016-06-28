<?php

namespace PP\Portal\Module;

use Slim\Http\UploadedFile;
use Valitron\Validator as Validator;

/**
 * handle of fileUploadModule from Slim3.
 */
class FileUploadModule
{
    /**
     * @var UploadedFile
     */
    public $file;

    private $validationRule = [];

    public $validationMsg = [];

    //put your code here
    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * array('image/png', 'image/gif').
     *
     * @param string[] $array
     */
    public function setAllowMimetype($array)
    {
        $this->validationRule['in'] = [
                ['type', $array]
            ];
    }

    /**
     * file is no larger than 5M (use "B", "K", M", or "G").
     *
     * @param string $size
     */
    public function setAllowFilesize($size)
    {
        $this->validationRule['max'] = [
                ['size', $this->humanReadableToBytes($size)]
            ];
    }

    public function getError()
    {
        return $this->file->getError();
    }

    public function getClientFilename()
    {
        return $this->file->getClientFilename();
    }

    /**
     * @param string $path
     */
    public function moveTo($path)
    {
        $this->file->moveTo($path);
    }

    public function isValid()
    {
        if ($this->file->getError() !== UPLOAD_ERR_OK) {
            $this->validationMsg[] = $this->file->getError();

            return false;
        }

        $v = new Validator(array(
                'size' => $this->file->getSize() ,
                'type' => $this->file->getClientMediaType()
            ));
        $v->rules($this->validationRule);

        if ( $v->validate()){
            return true;
        }

        $this->validationMsg = (array)$v->errors();
        return false;
    }

    public function getValidationMsg()
    {
        return $this->validationMsg;
    }

    /**
     * Convert human readable file size (e.g. "10K" or "3M") into bytes.
     *
     * @param string $input
     *
     * @return int
     */
    private function humanReadableToBytes($input)
    {
        $number = (int) $input;
        $units = [
            'b' => 1,
            'k' => 1024,
            'm' => 1048576,
            'g' => 1073741824,
        ];
        $unit = strtolower(substr($input, -1));
        if (isset($units[$unit])) {
            $number = $number * $units[$unit];
        }

        return $number;
    }
}
