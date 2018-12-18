<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class MeetingStandardAsset extends AssetBundle

{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/meeting.js',
    ];
    
    public $depends = [
        'frontend\assets\AppAsset',
     ];
}
