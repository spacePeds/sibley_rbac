<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\SubPage */

$this->title = Yii::t('app', 'Create Sub Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sub Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-page-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'documents' => $documents,
    ]) ?>

</div>
