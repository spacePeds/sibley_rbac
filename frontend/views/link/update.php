<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Link */

$this->title = 'Update Quick-Link: ' . $model->label;
$this->params['breadcrumbs'][] = ['label' => 'Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
//echo print_r($model,true);
?>
<div class="link-update">

    <h3 class="ml-3"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'linkGroups'  => $linkGroups
    ]) ?>

</div>
