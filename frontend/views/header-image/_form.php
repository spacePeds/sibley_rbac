<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\HeaderImage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="header-image-form">

    <?php $form = ActiveForm::begin([
        'id'=>'header_image_form',
        'options' => ['enctype' => 'multipart/form-data'],
        //'enableAjaxValidation'  => true,
        //'validationUrl'     => Url::toRoute('header-image/validation')
    ]); ?>

    <?= $form->field($model, 'image_idx')->hiddenInput()->label(false) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'uploadedImage')->fileInput(['id'=>"Fileinput"])->label(false) ?>
        </div>
        <div class="col-md-6">
            <div id="uploadMessages"></div>
            <div class="img_preview">
                <div class="im_progress" style="display:none;">
                    <img class="loader_img" src="/img/ajax-loader.gif">
                </div>
                <img src="" id="img_preview" class="img-thumbnail">
            </div>
        </div>
    </div>
    
    

    <?= $form->field($model, 'display')->dropDownList([
        'normal' => 'Normal', 
        'parallax' => 'Parallax',
        'rounded' => 'Rounded',
    ], ['prompt' => 'Please Select A Display Type']) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'brightness', [
                'errorOptions'  => [
                    'class' => 'form-control-invalid-feedback',
                ],
                'template' => '{label}{input}
                <small id="url_exHelp" class="form-text text-muted">Darken the image by entering a decimal value between 0 -1</small>
                {error}'
            ])->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'offset', [
                'errorOptions'  => [
                    'class' => 'form-control-invalid-feedback',
                ],
                'template' => '{label}{input}
                <small id="url_exHelp" class="form-text text-muted">Applies to Parallax Display Type Only</small>
                {error}'
            ])->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    
    <?= $form->field($model, 'height')->textInput() ?>
    
    <?= $form->field($model, 'position')->dropDownList([
        'left' => 'Left', 
        'right' => 'Right',
        'center' => 'Center',
    ], ['prompt' => 'Choose how you would like the image to be alligned']) ?>
    
    <?= $form->field($model, 'sequence')->textInput() ?>

    <div class="form-group">
        <?= Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success','id' => 'headerImgSubmit']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
