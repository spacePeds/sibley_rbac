<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class MeetingAdminAsset extends AssetBundle

{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/meetingAdmin.js',
    ];
    
    public $depends = [
        'frontend\assets\MeetingStandardAsset',
     ];
}
