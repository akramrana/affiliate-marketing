<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
use yii\base\Model;
use yii\web\UploadedFile;
/**
 * Description of JsonUpload
 *
 * @author akram
 */
class JsonUpload extends Model{
    
    public $file;

    public function rules()
    {
        return [
            [['file'], 'required', 'message' => \Yii::t('app', 'Upload at least one file.')],
            [['file'], 'file','extensions' => 'json'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' => \Yii::t('app', 'Select json file'),
        ];
    }
}
