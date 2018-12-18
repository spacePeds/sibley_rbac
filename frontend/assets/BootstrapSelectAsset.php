<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class BootstrapSelectAsset extends AssetBundle

{
    public $sourcePath = '@vendor/snapappointments/bootstrap-select/dist';
    public $css = [
        'css/bootstrap-select.css',
    ];
    public $js = [
        'js/bootstrap-select.js',
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
