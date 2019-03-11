<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use frontend\assets\BootstrapDatetimepickerAsset;

BootstrapDatetimepickerAsset::register($this);

/* @var $this yii\web\View */
/* @var $model frontend\models\Event */
/* @var $form yii\widgets\ActiveForm */
$js = <<<EOF
console.log('initing datetimepicker');
    //https://github.com/pingcheng/bootstrap4-datetimepicker
    //http://eonasdan.github.io/bootstrap-datetimepicker/
    // Using font-awesome 5 icons
    $.extend(true, $.fn.datetimepicker.defaults, {
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'fas fa-calendar-check',
            clear: 'far fa-trash-alt',
            close: 'far fa-times-circle'
        }
    });
    $('#event-start_dt').datetimepicker();
    $('#event-end_dt').datetimepicker();
EOF;
$this->registerJs($js);
//echo '<pre>' . print_r($model,true) . '</pre>';
?>

<div class="event-form">
    
    <!--id important for validation-->
    <?php $form = ActiveForm::begin([
        'id'                    => 'calForm',
        'enableAjaxValidation'  => true,
        'validationUrl'     => Url::toRoute('event/validation')
    ]); ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => '6'],
        'preset' => 'custom',
        'clientOptions' => [
            'toolbarGroups' => [
                ['name' => 'basicstyles', 'groups' => [ 'basicstyles']],
                ['name' => 'clipboard', 'groups' => [ 'clipboard', 'undo' ]],
                ['name' => 'editing', 'groups' => [ 'spellchecker' ]],
                '/',               
                ['name' => 'paragraph', 'groups' => [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ]],
                ['name' => 'links', 'groups' => [ 'links' ]],
            ],
            'removeButtons' => 'Flash,Iframe,Language,Save,NewPage'
        ]
        
    ]) ?>

<?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>

    <?php if (count($group) > 1) : ?>
        <?= $form->field($model, 'group')->dropDownList($group,['prompt'=>'Choose an Option']) ?>
    <?php else: ?>
        <?= $form->field($model, 'group')->hiddenInput(['value'=> $group[0]])->label(false); ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'start_dt', [
                //https://github.com/yiisoft/yii2-bootstrap4/issues/36
                'errorOptions'  => [
                    'class' => 'form-control-invalid-feedback date',
                ],
                'template' => '{label}<div class="input-group date">{input}<div class="input-group-append">
                    <button class="btn btn-outline-secondary dtPicker" type="button" id="button-start_dt"><i class="far fa-calendar-alt"></i></button>
                    </div></div><small id="start_dtHelpBlock" class="form-text text-muted">Click on the calendar icon to choose a date</small>{error}'
            ])->textInput(['placeholder' => 'MM/DD/YYYY', 'aria-describedby' => 'start_dtHelpBlock']) ?>

            <?= $form->field($model, 'all_day')->checkbox() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'end_dt', [
                //https://github.com/yiisoft/yii2-bootstrap4/issues/36
                'errorOptions'  => [
                    'class' => 'form-control-invalid-feedback date',
                ],
                'template' => '{label}<div class="input-group date">{input}<div class="input-group-append">
                    <button class="btn btn-outline-secondary dtPicker" type="button" id="button-end_dt"><i class="far fa-calendar-alt"></i></button>
                    </div></div><small id="end_dtHelpBlock" class="form-text text-muted">Click on the calendar icon to choose a date</small>{error}'
            ])->textInput(['placeholder' => 'MM/DD/YYYY', 'aria-describedby' => 'end_dtHelpBlock']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'repeat_interval')->dropDownList($repition,['default'=>0]) ?>
        </div>
    </div>
    
    <?= $form->field($model, 'pdfFile')->fileInput(); ?>

    <?php
        if ($model->pdfFile) {
            echo 'document exists';
            echo $model->pdfFile;
        }
    ?>

    
    <div class="form-group text-right">
        <button type="button" class="btn btn-link" data-dismiss="modal" aria-label="Close">Cancel</button>
        <?php if ($model->id > 0): ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this event?'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
            'class' => 'btn btn-success'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

