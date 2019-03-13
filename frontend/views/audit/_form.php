<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Audit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="audit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'record_id')->textInput() ?>

    <?= $form->field($model, 'field')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'old_value')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'new_value')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'update_user')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
