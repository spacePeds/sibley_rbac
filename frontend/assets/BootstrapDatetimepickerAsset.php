<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class BootstrapDatetimepickerAsset extends AssetBundle

{
    public $sourcePath = '@node/pc-bootstrap4-datetimepicker/build';
    public $css = [
        'css/bootstrap-datetimepicker.min.css',
    ];
    public $js = [
        'js/bootstrap-datetimepicker.min.js',
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
