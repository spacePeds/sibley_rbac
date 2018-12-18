<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '//use.fontawesome.com/releases/v5.3.1/css/all.css',
        //'css/site.css',
        YII_ENV_DEV ? 'css/sibley.css' : 'css/sibley.min.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //https://stackoverflow.com/questions/30327781/yii2-bootstrapasset-is-not-loading-bootstrap-js
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
}
