<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\SubPageDocument */

$this->title = Yii::t('app', 'Create Sub Page Document');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sub Page Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-page-document-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
