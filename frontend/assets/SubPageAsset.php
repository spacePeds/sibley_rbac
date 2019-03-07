<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class SubPageAsset extends AssetBundle

{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/subPage.js',
    ];
    
    public $depends = [
        'frontend\assets\AppAsset',
     ];
}
