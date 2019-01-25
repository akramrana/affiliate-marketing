<?php
/**
 * Created by PhpStorm.
 * User: Chirag Panchal
 * Date: 12/17/2017
 * Time: 6:54 PM
 */

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class ExcelUpload extends Model
{
    public $file;

    public function rules()
    {
        return [
            [['file'], 'required', 'message' => \Yii::t('app', 'Upload at least one file.')],
            [['file'], 'file','extensions' => 'xlsx,xls,csv'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' => \Yii::t('app', 'Select excel file'),
        ];
    }

}