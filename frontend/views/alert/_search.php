<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AlertSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="alert-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'group') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'message') ?>

    <?= $form->field($model, 'start_dt') ?>

    <?php // echo $form->field($model, 'end_dt') ?>

    <?php // echo $form->field($model, 'created_dt') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
