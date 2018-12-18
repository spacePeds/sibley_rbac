<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class PageAsset extends AssetBundle

{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/page.js',
    ];
    
    public $depends = [
        'frontend\assets\AppAsset',
     ];
}
