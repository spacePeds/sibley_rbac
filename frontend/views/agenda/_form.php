<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Url;



Yii::$app->assetManager->bundles = [
    'yii\bootstrap\BootstrapPluginAsset' => false,
    'yii\bootstrap\BootstrapAsset' => false,
    'yii\web\JqueryAsset' => false,
    ];


/* @var $this yii\web\View */
/* @var $model common\models\Agenda */
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

<div class="agenda-form">

    <?php $form = ActiveForm::begin([
        'id'                    => 'agendaForm',
        'enableAjaxValidation'  => true,
        'validationUrl'     => Url::toRoute('agenda/validation')
    ]); ?>

    <?= $form->field($model, 'type')->dropDownList(['regular'=> 'Regular Meeting','special' => 'Special Meeting'],['prompt'=>'Choose an Option']) ?>

    <?= $form->field($model, 'date', [
        //https://github.com/yiisoft/yii2-bootstrap4/issues/36
        'errorOptions'  => [
            'class' => 'form-control-invalid-feedback',
        ],
        'template' => '{label}<div class="input-group date">{input}<div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="button-date"><i class="far fa-calendar-alt"></i></button>
            </div></div><small id="dateHelpBlock" class="form-text text-muted">Click on the calendar icon to choose a date</small>{error}'
    ])->textInput(['placeholder' => 'MM/DD/YYYY', 'aria-describedby' => 'dateHelpBlock']) ?>

    <div class="invalidFix"> 
    <?= $form->field($model, 'body')->widget(CKEditor::className(), [
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
</div>

    <div class="form-group text-right">
        <?php if ($model->id > 0 && Yii::$app->user->can('delete_cheeseburger')): ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-outline-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this agenda? Any associated minutes will also be deleted.'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
            'class' => 'btn btn-primary'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
