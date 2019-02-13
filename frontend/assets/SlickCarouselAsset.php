<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class SlickCarouselAsset extends AssetBundle

{
    public $sourcePath = '@vendor/bower-asset/slick-carousel/slick';
    public $css = [
        'slick.css',
    ];
    public $js = [
        'slick.min.js',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
     ];
}
