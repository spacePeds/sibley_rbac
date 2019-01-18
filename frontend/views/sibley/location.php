<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

if (count($details) < 1) {
    $this->title = 'Page not found.';
    $body = '<p>This page is currently unconfigured.</p>';
} else {
    $this->title = $details->title;
    $body = $details->body;
}


$this->params['breadcrumbs'][] = $this->title;
?>
<?php //echo '<pre>' . print_r($details,true) . '</pre>' ?>


<section id="location">
    <div class="container bg-white clearfix">
        <?php if (Yii::$app->user->can('update_location')) : ?>
            <div class="float-right adminFloaterRev2 shadow-sm p-1 mb-2 bg-white rounded">
                <a href="<?= Url::to(['/page/update']) . '/'.$key ?>" role="button" class="btn btn-primary">Edit Location Page</a>
            </div>
        <?php endif; ?>
        <?= $body ?>
    </div>
</section>
