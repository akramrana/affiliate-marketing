<?php
namespace app\assets;

use yii\web\AssetBundle;


class SelectAsset extends AssetBundle
{

    public $baseUrl = '@web/plugins/';

    public $css = [
        'select2-3.5.2/select2.css',
        'select2-3.5.2/select2-bootstrap.css',
    ];

    public $js = [
        'select2-3.5.2/select2.min.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}