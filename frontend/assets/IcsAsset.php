<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class IcsAsset extends AssetBundle

{
    public $sourcePath = '@vendor/bower-asset/ics.js';
    public $css = [
        
    ];
    public $js = [
        'ics.deps.min.js',
        //'ics.min.js'
    ];
    public $depends = [
        'frontend\assets\AppAsset',
        
     ];
}
