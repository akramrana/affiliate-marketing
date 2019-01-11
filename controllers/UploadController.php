<?php

namespace app\controllers;

use Yii;
use yii\web\UploadedFile;

class UploadController extends \yii\web\Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionCommon($attribute) {
        $imageFile = UploadedFile::getInstanceByName($attribute);
        //$directory = \Yii::getAlias('@app/web/uploads') . DIRECTORY_SEPARATOR . Yii::$app->session->id . DIRECTORY_SEPARATOR;
        $directory = \Yii::getAlias('@app/web/uploads') . DIRECTORY_SEPARATOR;
        if ($imageFile) {
            $filetype = mime_content_type($imageFile->tempName);
            $filesize = $imageFile->size / 1024;
            $allowed = array('image/png', 'image/jpeg', 'image/gif');
            if (!in_array(strtolower($filetype), $allowed)) {
                return json_encode(['files' => [
                        'error' => "File type not supported",
                    ]
                ]);
            } elseif ($filesize > 1000) {
                return json_encode(['files' => [
                        'error' => "File is too large. Max 1MB allowed",
                    ]
                ]);
            } else {
                $uid = uniqid(time(), true);
                $fileName = $uid . '.' . $imageFile->extension;
                $filePath = $directory . $fileName;
                if ($imageFile->saveAs($filePath)) {
                    $path = \yii\helpers\BaseUrl::home() . 'uploads/' . $fileName;

                    return json_encode([
                        'files' => [
                            'name' => $fileName,
                            'size' => $imageFile->size,
                            "url" => $path,
                            "thumbnailUrl" => $path,
                            "deleteUrl" => 'image-delete?name=' . $fileName,
                            "deleteType" => "POST",
                            'error' => ""
                        ]
                    ]);
                }
            }
        }
        return '';
    }

}
