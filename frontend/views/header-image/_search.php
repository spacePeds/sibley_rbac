<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\HeaderImageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="header-image-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'image_path') ?>

    <?= $form->field($model, 'image_idx') ?>

    <?= $form->field($model, 'paralex') ?>

    <?= $form->field($model, 'brightness') ?>

    <?php // echo $form->field($model, 'offset') ?>

    <?php // echo $form->field($model, 'height') ?>

    <?php // echo $form->field($model, 'path') ?>

    <?php // echo $form->field($model, 'last_edit') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
