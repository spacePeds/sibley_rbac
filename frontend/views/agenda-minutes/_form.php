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
?>

<div class="minutes-form">

    <?php $form = ActiveForm::begin([
        'id'                    => 'minutesForm',
        'enableAjaxValidation'  => true,
        'validationUrl'     => Url::toRoute('agenda-minutes/validation')
    ]); ?>

    <?= $form->field($model, 'agenda_id')->hiddenInput()->label(false); ?>
    
    <?= $form->field($model, 'attend') ?>

    <?= $form->field($model, 'absent') ?>

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

    <?= $form->field($model, 'video') ?>

    <div class="form-group text-right">
        <button type="button" class="btn btn-link" data-dismiss="modal" aria-label="Close">Cancel</button>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
            'class' => 'btn btn-success'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
