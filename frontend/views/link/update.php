<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Link */

$this->title = 'Update Link: ' . $model->label;
$this->params['breadcrumbs'][] = ['label' => 'Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
//echo print_r($model,true);
?>
<div class="link-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'linkGroups'  => $linkGroups
    ]) ?>

</div>
