<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use frontend\assets\BootstrapSelectAsset;
use yii\helpers\Url;
use frontend\assets\BusinessAsset;

BusinessAsset::register($this);

BootstrapSelectAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\BusinessWithCategory */
/* @var $modelsContactMethod common\models\BusinessWithCategory */
/* @var $form yii\widgets\ActiveForm */
$js = <<<EOF
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title-contact").each(function(index) {
        jQuery(this).html("Contact: " + (index + 1))
    });
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title-contact").each(function(index) {
        jQuery(this).html("Contact: " + (index + 1))
    });
});

$('#businesswithcategories-imgfile').change();
jQuery('#businesswithcategories-imgfile').on('change',function(){
    //get the file name
    var fileName = $(this).val();
    //replace the "Choose a file" label
    $(this).next('.custom-file-label').html(fileName);
})

$(".selectpicker").selectpicker(
    {"BootstrapVersion":3}
);
EOF;
$this->registerJs($js);
?>


<div class="business-form container">

    <?php 
    $form = ActiveForm::begin([
        'id' => 'business-form',
        'enableAjaxValidation' => false,
    ]); ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true, 'placeholder' => "Example: http://www.website.com"]) ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'member')->dropDownList([ 1 => 'Yes', 0 => 'No', ], ['prompt' => 'Is this entity a chamber member?']) ?>

    <?= $form->field($model, 'imgFile', [
        'errorOptions'  => [
            'class' => 'form-control-invalid-feedback',
        ],
        'inputOptions'  => [
            'class' => 'custom-file-input',
        ],
        'labelOptions'  => [
            'class' => 'custom-file-label',
        ],
        'template' => '<label>Organization Image (optional)</label><div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">Upload</span>
        </div>
        <div class="custom-file">{input}{label}
        </div></div>
        {error}'
    ])->fileInput(); ?>
    
    <?php if (isset($model->imgFileUrl)): ?>
        <div><img src="/<?=$model['imgFileUrl']?>" class="rounded mb-3 ml-3 shadow-sm" height="100px"></div>
    <?php endif; ?>

    <div class="card h-100 mb-3">
        <div class="card-header">Category Selection</div>
        <div class="card-body row">
            <div class="col-md-6">
            <?= $form->field($model, 'category_ids')
                ->listBox($categories, [
                    'multiple' => true,
                    'class' => 'form-control selectpicker',
                    'data-live-search' => 'true',
                    'data-max-options' => 4,
                    'data-size' => 6,
                    'title' => 'Choose up to 4 categories'
                ])
                /* or, you may use a checkbox list instead */
                /* ->checkboxList($categories) */
                ->hint('Select any of the following categories which best describe this business.');?>
            </div>
            <div class="col-md-6 border border-light rounded text-center">
                <h6 class="mt-2">Category not in list?</h6>
                <div class="card-text">
                    <?= Html::button('Add Category', [
                        'value' => Url::to('@web/category/create'), 
                        'class' => 'btn btn-primary',
                        'id' => 'btnModalCategory']) ?>
                </div>
            </div>
        </div>
    </div>


    
    


    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
        'widgetBody' => '.container-items', // required: css class selector
        'widgetItem' => '.item', // required: css class
        'limit' => 5, // the maximum times, an element can be cloned (default 999)
        'min' => 0, // 0 or 1 (default 1)
        'insertButton' => '.add-item', // css class
        'deleteButton' => '.remove-item', // css class
        'model' => $modelsContact[0],
        'formId' => 'business-form',
        'formFields' => [
            'method',
            'contact',
            'description',
        ],
    ]); ?>
    <div class="card mb-4">
        <div class="card-header">
            Contact Methods
            <button type="button" class="float-right add-item btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</button>
            <div class="clearfix"></div>
        </div>
        <div class="card-body container-items"><!-- widgetContainer -->
            <?php foreach ($modelsContact as $index => $modelContact): ?>
                <div class="item card mb-2"><!-- widgetBody -->
                    <div class="card-header">
                        <span class="">Contact: <?= ($index + 1) ?></span>
                        <button type="button" class="pull-right remove-item btn btn-danger btn-sm"><i class="fa fa-minus"></i></button>
                        <div class="clearfix"></div>
                    </div>
                    <div class="card-body">
                        <?php
                            // necessary for update action.
                            if (!$modelContact->isNewRecord) {
                                echo Html::activeHiddenInput($modelContact, "[{$index}]id");
                            }
                        ?>

                        <div class="row">
                            <div class="col-sm-4">
                                <?= $form->field($modelContact, "[{$index}]method")->dropDownList([ 
                                    'email' => 'Email', 
                                    'phone' => 'Phone', 
                                    'fax'   => 'FAX'
                                ], ['prompt' => 'Pick a contact method']) ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($modelContact, "[{$index}]contact")->textInput(['maxlength' => true])->hint('Phone example: 1234567890<br/>Email Example: person@domain.com') ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($modelContact, "[{$index}]description")->textInput(['maxlength' => true, 'placeholder' => '(Optional)']) ?>
                            </div>
                        </div><!-- end:row -->

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
            'class' => 'btn btn-primary'
        ]) ?>
    </div>

    <?php DynamicFormWidget::end(); ?>

    <?php ActiveForm::end(); ?>

</div>
