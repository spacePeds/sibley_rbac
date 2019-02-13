<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\SubPageDocument */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sub-page-document-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sub_page_id')->textInput() ?>

    <?= $form->field($model, 'document_id')->textInput() ?>

    <?= $form->field($model, 'allignment')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
