<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $details->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<?php //echo '<pre>' . print_r($details,true) . '</pre>' ?>
<?php if (Yii::$app->user->can('update_location')) : ?>
    <div class="adminFloater shadow-sm p-3 mb-5 bg-white rounded">
        <a href="<?= Url::to(['/page/update']) . '/'.$details->id ?>" role="button" class="btn btn-primary">Edit Location Page</a>
    </div>
<?php endif; ?>

<section id="location">
    <div class="container bg-white">
        <?= $details->body ?>
    </div>
</section>
