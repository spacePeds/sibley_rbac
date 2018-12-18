<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class CalendarAdminAsset extends AssetBundle

{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/calendarAdmin.js',
    ];
    
    public $depends = [
        'frontend\assets\AppAsset',
     ];
}
