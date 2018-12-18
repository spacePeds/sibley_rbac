<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use frontend\assets\BootstrapDatepickerAsset;

BootstrapDatepickerAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\Alert */
/* @var $form yii\widgets\ActiveForm */
$js = <<<EOF
console.log('initing datepicker');
$('.date').datepicker({
    format: 'mm/dd/yyyy',
    todayHighlight: true
});
EOF;
$this->registerJs($js);
?>

<div class="alert-form">

    <?php $form = ActiveForm::begin([
        'id' => 'active-form',
        'options' => [
            'class' => '',
            'enctype' => 'multipart/form-data',

        ],
        
        //https://github.com/yiisoft/yii2-bootstrap4/issues/36
        //'fieldConfig' => [
        //    'template' => "{label}\n<div class=\"input-group mb-3 date\">{input}<div class=\"input-group-append\">
        //    <button class=\"btn btn-outline-secondary\" type=\"button\" id=\"button-start_dt\"><i class=\"far fa-calendar-alt\"></i></button>
        //    </div></div>{error}</div>",
        //    'labelOptions' => ['class' => ''],
        //],
    ]); ?>

    <?= Html::errorSummary($model); ?>

    <?php if (count($group) > 1) : ?>
        <?= $form->field($model, 'group')->dropDownList($group,['prompt'=>'Choose an Option']) ?>
    <?php else: ?>
        <?= $form->field($model, 'group')->hiddenInput(['value'=> $group[0]])->label(false); ?>
    <?php endif; ?>

    <?= $form->field($model, 'type')->dropDownList([
        'danger'=> 'Alert',
        'info' => 'Notice',
        'secondary' => 'Normal'
    ],['prompt'=>'Choose an Option']) ?>

    <?= $form->field($model, 'message')->textInput(['maxlength' => true]) ?>

    
    <?php //echo $form->field($model, 'start_dt')->textInput() ?>
    <?= $form->field($model, 'start_dt', [
        //https://github.com/yiisoft/yii2-bootstrap4/issues/36
        'errorOptions'  => [
            'class' => 'form-control-invalid-feedback',
        ],
        'template' => '{label}<div class="input-group date">{input}<div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="button-start_dt"><i class="far fa-calendar-alt"></i></button>
            </div></div><small id="start_dtHelpBlock" class="form-text text-muted">Click on the calendar icon to choose a date</small>{error}'
    ])->textInput(['placeholder' => 'MM/DD/YYYY', 'aria-describedby' => 'start_dtHelpBlock']) ?>
    
    
    
    <?= $form->field($model, 'end_dt', [
        //https://github.com/yiisoft/yii2-bootstrap4/issues/36
        'errorOptions'  => [
            'class' => 'form-control-invalid-feedback',
        ],
        'template' => '{label}<div class="input-group date">{input}<div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="button-end_dt"><i class="far fa-calendar-alt"></i></button>
            </div></div><small id="end_dtHelpBlock" class="form-text text-muted">Click on the calendar icon to choose a date</small>{error}'
    ])->textInput(['placeholder' => 'MM/DD/YYYY', 'aria-describedby' => 'end_dtHelpBlock']) ?>

    <?php //echo $form->field($model, 'end_dt') ?>

    

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 
/*
//works
//https://bootstrap-datepicker.readthedocs.io/en/latest/markup.html#date-range
<div class="input-group input-daterange date">
        <input type="text" class="form-control" value="2012-04-05">
        <div class="input-group-addon">to</div>
        <input type="text" class="form-control" value="2012-04-19">
    </div>

//test code
<div class="form-group">
        <?= Html::activeLabel($model, 'start_dt'); ?>
        <div class="input-group mb-3 date">
            <?= Html::activeTextInput($model, 'start_dt', ['class' => 'form-control', 'placeholder' => 'MM/DD/YYYY']); ?>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="button-start_dt"><i class="far fa-calendar-alt"></i></button>
            </div>
            <?= Html::error($model, 'start_dt'); ?>
        </div>
    </div>
*/
?>