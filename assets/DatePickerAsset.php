<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\assets;
use yii\web\AssetBundle;
/**
 * Description of DatePickerAsset
 *
 * @author akram
 */
class DatePickerAsset extends AssetBundle{
    
    public $baseUrl = '@web/plugins/';

    public $css = [
        'bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css',
    ];

    public $js = [
        'bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
