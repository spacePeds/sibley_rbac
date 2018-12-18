<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class UploadProgressAsset extends AssetBundle

{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/uploadProgress.js',
    ];
    
    public $depends = [
        'frontend\assets\AppAsset',
     ];
}
