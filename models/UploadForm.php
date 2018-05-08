<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * ContactForm is the model behind the contact form.
 */
class UploadForm extends Model
{
    /** @var UploadedFile */
    public $file;

    public $fullPath;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['file', 'file', 'types'=>'xslx', 'safe' => false], 'required'],
        ];
    }

    /**
     * Saves the Uploaded file to the temp directory
     * @return bool
     */
    public function save()
    {
        $this->fullPath = '/tmp/'. md5(microtime()). "." . $this->file->getExtension();

        return $this->file->saveAs($this->fullPath);
    }

    /**
     * Clears the temporary file.
     * @return bool
     */
    public function clear() {
        return unlink($this->fullPath);
    }
}
