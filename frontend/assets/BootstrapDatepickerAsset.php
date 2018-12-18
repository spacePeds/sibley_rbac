<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class BootstrapDatepickerAsset extends AssetBundle

{
    public $sourcePath = '@vendor/bower-asset/bootstrap-datepicker/dist';
    public $css = [
        'css/bootstrap-datepicker.min.css',
    ];
    public $js = [
        'js/bootstrap-datepicker.min.js',
    ];
    public $publishOptions = [
        'only' => [
            'css/*',
            'js/*',
        ]
    ];
    public $depends = [
        'frontend\assets\AppAsset',
     ];
}
