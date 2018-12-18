<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use frontend\assets\BootstrapSelectAsset;
use yii\helpers\Url;
use yii\bootstrap4\Modal;
use frontend\assets\BusinessAsset;

BusinessAsset::register($this);

BootstrapSelectAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\BusinessWithCategory */
/* @var $modelsContactMethod common\models\BusinessWithCategory */
/* @var $form yii\widgets\ActiveForm */
$js = '
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
$(".selectpicker").selectpicker(
    {"BootstrapVersion":3}
);
';
$this->registerJs($js);
?>


<div class="business-form">

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

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'member')->dropDownList([ 1 => 'Yes', 0 => 'No', ], ['prompt' => 'Is this entity a chamber member?']) ?>

    <div class="row mb-2">
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
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">Category not in list?</div>
                <div class="card-body">
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
                                <?= $form->field($modelContact, "[{$index}]contact")->textInput(['maxlength' => true]) ?>
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
<?php
    Modal::begin([
        
        'id' => 'genericModal'
    ]);
    echo '<div id="modalContent"></div>';
    Modal::end();

?>
