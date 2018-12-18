<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class CkEditorAsset extends AssetBundle

{
    public $sourcePath = '@node/@ckeditor/ckeditor5-build-classic/build';
    
    public $js = [
        'ckeditor.js',
    ];
    
    public $depends = [
        'frontend\assets\AppAsset',
     ];
}
