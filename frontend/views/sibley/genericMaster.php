<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\SubPageAsset;

if (Yii::$app->user->can('update_subPage')) {
    SubPageAsset::register($this); 
}

$title = isset($page['title']) ? $page['title'] : 'Page Not Found.';

//echo '<pre>' . print_r($page,true) . '</pre>';

//import phone# formatting functions
$this->render('_helperFormatPhone', []);

$this->params['breadcrumbs'][] = $title;
?>
<?php if (!empty($page['fb_token'])): ?>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=<?=$page['fb_token']?>&autoLogAppEvents=1"></script>
<?php endif; ?>

<div id="page_<?=$page['id']?>" class="container">
    <?php echo ($page['sub_pages'] > 0) ? $this->render('_subPageTemplate', ['page'=>$page]) : $this->render('_basicPageTemplate', ['page'=>$page]); ?>
</div>
