<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class EkkoLightboxAsset extends AssetBundle

{
    public $sourcePath = '@vendor/bower-asset/ekko-lightbox/dist';
    public $css = [
        'ekko-lightbox.css',
    ];
    public $js = [
        'ekko-lightbox.min.js',
    ];
    
    public $depends = [
        'frontend\assets\AppAsset',
     ];
}
