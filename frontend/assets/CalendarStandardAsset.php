<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class CalendarStandardAsset extends AssetBundle

{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/calendar.js',
    ];
    
    public $depends = [
        'frontend\assets\AppAsset',
     ];
}
