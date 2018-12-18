<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class StaffAsset extends AssetBundle

{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/staff.js',
    ];
    
    public $depends = [
        'frontend\assets\AppAsset',
     ];
}
