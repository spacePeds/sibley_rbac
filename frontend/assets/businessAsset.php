<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class BusinessAsset extends AssetBundle

{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/business.js',
    ];
    
    public $depends = [
        'frontend\assets\AppAsset',
     ];
}
