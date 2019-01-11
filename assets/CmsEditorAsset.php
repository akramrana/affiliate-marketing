<?php
namespace app\assets;

use yii\web\AssetBundle;


class CmsEditorAsset extends AssetBundle
{
    public $baseUrl = '@web/plugins/';

    public $css = [
        'summernote/dist/summernote.css',
        'summernote/dist/summernote-bs3.css'
    ];

    public $js = [
        'summernote/dist/summernote.min.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}