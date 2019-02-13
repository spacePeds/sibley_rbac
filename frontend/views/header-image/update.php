<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\HeaderImage */

$this->title = Yii::t('app', 'Update Header Image: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Header Images'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="header-image-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
