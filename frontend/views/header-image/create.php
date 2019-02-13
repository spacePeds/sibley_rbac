<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\HeaderImage */

$this->title = Yii::t('app', 'Create Header Image');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Header Images'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="header-image-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
