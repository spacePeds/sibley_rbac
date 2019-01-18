<?php
// MyPagerAsset.php
namespace frontend\widgets\mylinkpager;

use yii\web\AssetBundle;

class MyLinkPagerAsset extends AssetBundle
{
    public $js = [
        'js/mylinkpager.js'
    ];

    public $css = [
        /* You can add extra CSS file here if you need */
        // 'css/demopager.css'
    ];

    public $depends = [
        // we will use jQuery
        'yii\web\JqueryAsset'
    ];

    public function init()
    {   
        // Base path of current widget
        $this->sourcePath = __DIR__ . "/assets";
        parent::init();
    }
}