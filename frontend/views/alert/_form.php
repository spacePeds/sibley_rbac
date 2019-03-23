<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Alert */
/* @var $form yii\widgets\ActiveForm */
$js = <<<EOF
$('.date').datepicker({
    format: 'mm/dd/yyyy',
    todayHighlight: true
});
$('option[value="danger"]').addClass('bg-danger');
$('option[value="info"]').addClass('bg-info');
$('option[value="secondary"]').addClass('bg-secondary');
$('option[value="warning"]').addClass('bg-warning');
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
        'danger'=> 'Severe Alert',
        'warning' => 'Moderate Alert',
        'info' => 'General Notice',
        'secondary' => 'Informational Notice'
        
    ],['prompt'=>'Choose an Option']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <div class="row">
        <div class="col-md-6">
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
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'end_dt', [
                //https://github.com/yiisoft/yii2-bootstrap4/issues/36
                'errorOptions'  => [
                    'class' => 'form-control-invalid-feedback',
                ],
                'template' => '{label}<div class="input-group date">{input}<div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="button-end_dt"><i class="far fa-calendar-alt"></i></button>
                    </div></div><small id="end_dtHelpBlock" class="form-text text-muted">Click on the calendar icon to choose a date</small>{error}'
            ])->textInput(['placeholder' => 'MM/DD/YYYY', 'aria-describedby' => 'end_dtHelpBlock']) ?>
        </div>
    </div>
    
    <div class="form-group text-right">
        <button type="button" class="btn btn-link" data-dismiss="modal" aria-label="Close">Cancel</button>
        
        <?php if ($model->id > 0): ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this site-wide alert?'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
        
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