<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\assets;
use yii\web\AssetBundle;
/**
 * Description of WebsiteAsset
 *
 * @author akram
 */
class WebsiteAsset extends AssetBundle{
    //put your code here
    public $baseUrl = '@web/theme/';

    public $css = [
        'https://fonts.googleapis.com/css?family=Vidaloka',
        'assets/fonts/aileron/stylesheet.css',
        'assets/font-awesome/css/font-awesome.min.css',
        'assets/menuzord/css/menuzord.css',
        'assets/menuzord/css/skins/menuzord-strip.css',
        /*'assets/revolution/css/settings.css',
        'assets/revolution/css/layers.css',
        'assets/revolution/css/navigation.css',*/
        'assets/owl-carousel/assets/owl.carousel.min.css',
        'assets/owl-carousel/assets/owl.theme.default.min.css',
        'assets/bootstrap/css/bootstrap.min.css',
        'style.css',
        'assets/css/colors/color1.css',
        'custom.css',
    ];

    public $js = [
        'assets/js/jquery-2.1.3.min.js',
        'assets/bootstrap/js/bootstrap.min.js',
        'assets/menuzord/js/menuzord.js',
        'assets/js/jquery.sticky.js',
        'assets/js/clipboard.min.js',
        'assets/js/jquery.inview.min.js',
        //'assets/js/ajaxchimp.js',
        //'assets/js/ajaxchimp-config.js',
        'assets/js/script.js',
        'assets/owl-carousel/owl.carousel.min.js',
        /*'assets/revolution/js/jquery.themepunch.tools.min.js',
        'assets/revolution/js/jquery.themepunch.revolution.min.js',
        'assets/revolution/js/extensions/revolution.extension.video.min.js',
        'assets/revolution/js/extensions/revolution.extension.slideanims.min.js',
        'assets/revolution/js/extensions/revolution.extension.actions.min.js',
        'assets/revolution/js/extensions/revolution.extension.carousel.min.js',
        'assets/revolution/js/extensions/revolution.extension.layeranimation.min.js',
        'assets/revolution/js/extensions/revolution.extension.kenburn.min.js',
        'assets/revolution/js/extensions/revolution.extension.navigation.min.js',
        'assets/revolution/js/extensions/revolution.extension.migration.min.js',
        'assets/revolution/js/extensions/revolution.extension.parallax.min.js',*/
    ];

    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
        //'yii\bootstrap\BootstrapPluginAsset',
    ];
    
    public $jsOptions = ['position' => \yii\web\View::POS_END];
}
