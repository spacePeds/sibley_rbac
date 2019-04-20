<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class SortableAsset extends AssetBundle

{
    public $sourcePath = '@vendor/components/jqueryui';
    public $css = [
        
    ];
    public $js = [
        'jquery-ui.min.js',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
        
     ];
}
