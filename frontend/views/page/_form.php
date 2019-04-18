<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use frontend\assets\BootstrapSelectAsset;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Url;
use frontend\assets\PageAsset;

PageAsset::register($this);

BootstrapSelectAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\Page */
/* @var $form yii\widgets\ActiveForm */
//echo  var_dump($categories);
//$this->registerJs("CKEDITOR.plugins.addExternal('simage', '".Yii::getAlias('@web')."/plugins/simage_1.3/plugin.js', '');");
//$this->registerJs("CKEDITOR.plugins.addExternal('font', '".Yii::getAlias('@web')."/plugins/font_4.10.1/font/plugin.js', '');");


?>

<div class="page-form container">
    
    <?php 
    $form = ActiveForm::begin([
        'id' => 'page-form',
        'enableAjaxValidation' => false,
    ]); ?>

    <!--<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />-->

    <?php if (isset($role['superAdmin'])) : ?>
        <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>
    <?php else: ?>
        <?= $form->field($model, 'route')->hiddenInput()->label(false); ?>
        <div class="row">
            <div class="col">
            <dl>
            <dt>Route:</dt>
            <dd><?=$model->route ?></dd>
            </dl>
            </div>
            
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="headerImageToggle">
        <label class="form-check-label" for="headerImageToggle">Prepend Images?</label>
    </div>

    <div id="headerImageContainer" class="card mb-4" style="display:none;">
        <div class="card-header">
            Header Images
            <div class="clearfix small text-muted">Optional. Add any images you would like to prepend your page.</div>
        </div>
        <div class="card-body">
            <div class="uploadedImages d-flex flex-row">
                <?php //echo print_r($headImages, true); ?>
                <?php foreach ($headImages as $headImage): ?>
                    <div class="card" data-id="<?=$headImage['id']?>">
                        <img class="card-img-top img-thumbnail" style="max-height:100px;object-fit:cover;" src="<?=$headImage['image_path']?>">
                        <div class="card-body p-0 text-center">
                            <a href="#" class="deleteHeaderImage btn btn-outline-danger btn-sm" data-id="<?=$headImage['id']?>">Delete</a>
                            <a href="#" class="updateHeaderImage btn btn-outline-success btn-sm" data-id="<?=$headImage['id']?>">Update</a>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="uploadMessage"></div>
                    <button type="button" data-title="<?=$model->title?>" data-id="<?=$model->id?>" id="headerImageFormTrigger" class="btn btn-outline-primary">Add Image</button>
                    <div class="small text-muted">Images displayed here are just previews and do not represent how the image will be displayed on a live page</div>
                </div>
                
            </div>
            
        </div>
    </div>
    
    <div class="invalidFix">
        <?= $form->field($model, 'body')->widget(CKEditor::className(), [
            'options' => ['rows' => '6'],
            'preset' => 'custom',
            'clientOptions' => [
                'toolbarGroups' => [
                    ['name' => 'document', 'groups' => [ 'mode', 'document', 'doctools' ]],
                    ['name' => 'clipboard', 'groups' => [ 'clipboard', 'undo' ]],
                    ['name' => 'editing', 'groups' => [ 'find', 'selection', 'spellchecker', 'editing' ]],
                    '/',
                    ['name' => 'basicstyles', 'groups' => [ 'basicstyles', 'cleanup' ]],
                    ['name' => 'paragraph', 'groups' => [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ]],
                    ['name' => 'insert', 'groups' => [ 'insert' ]],
                    '/',
                    ['name' => 'styles', 'groups' => [ 'styles' ]],
                    ['name' => 'colors', 'groups' => [ 'colors' ]],
                    ['name' => 'tools', 'groups' => [ 'tools' ]],
                    ['name' => 'links', 'groups' => [ 'links' ]],
                    ['name' => 'others', 'groups' => [ 'others' ]],
                    ['name' => 'about', 'groups' => [ 'about' ]]
                ],
                'removeButtons' => 'Flash,Iframe,Language,Save,NewPage',
                //'contentsCss' => ['http://sibleyfront.test/assets/7a577269/js/bootstrap.bundle.js'],
                'allowedContent' => true
            ]
            /*
            'clientOptions' => [
                'extraPlugins' => 'font',   //['simage','font']
                'toolbarGroups' => [
                
                    //['name' => 'simage'], // <--- OUR NEW PLUGIN YAY!
                    ['name' => 'font']
                ]
            ]
            */
        ])->hint('Hint: To embed images in with text, first add the image to the available assets. This can be done by clicking the, "Add / Remove Image Assets" button at the top of this page.') ?>
    </div>

    <div class="card" id="fbOptions">
        <div class="card-header">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="fbToggle">
                <label class="form-check-label" for="fbToggle">Include <i class="fab fa-facebook-square"></i> Page Feed?</label>
            </div>
        </div>
        <div class="row card-body" style="display:none;">
            <div class="col">
                <?= $form->field($model, 'fb_token')->textInput(['maxlength' => true, 'placeholder' => 'Expl: 123456789123456']) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'fb_link')->textInput(['maxlength' => true, 'placeholder' => 'Expl: YourPageName']) ?>
            </div>
        </div>
    </div>
    
    <p>&nbsp;</p>

    <div class="card">
        <div class="row card-body">
            <div class="col">
                <?= $form->field($model, 'category_ids')->listBox($categories, [
                    'multiple' => true,
                    'class' => 'form-control selectpicker',
                    'data-live-search' => 'true',
                    'data-max-options' => 4,
                    'data-size' => 6,
                    'title' => 'Choose up to 4 categories'
                ])
                /* or, you may use a checkbox list instead */
                /* ->checkboxList($categories) */
                ->hint('Selecting a category will automatically retrieve all organizations matching that category and list them for you on this page.');?>
            </div>
            <div class="col">
                <?php if (Yii::$app->user->can('update_category')) : ?>
                    <div class="p-2 text-right"><?= Html::a(Yii::t('app', 'Update Categories'), [Url::to('/category')], ['class' => 'btn btn-outline-primary']) ?></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-footer">
            <div id="categoryDetails">

            </div>
        </div>
    </div>
    
    <p>&nbsp;</p>

    <?= $form->field($model, 'sub_pages')->dropDownList([
        '0' => 'No', 
        '1' => 'Yes',
    ], ['prompt' => '']) ?>


    <div class="form-group text-right">
    <button type="button" id="cancelButn" class="btn btn-link">Cancel</button>
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
</div>
<div class="modal fade" id="genericModal" tabindex="-1" role="dialog" aria-labelledby="genericModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="genericModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalContent">
                <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<?php

$js = <<<EOF
$(function() {
    
    /*
    ClassicEditor
        .create( document.querySelector( '#pagewithcategories-body' ), {
            ckfinder: {
                uploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
            }
         })
        .catch( error => {
            console.error( error );
    });

    $('#page-form').on('beforeValidate', function (event, messages, deferreds) {
        console.log(event, messages, deferreds);
        for(var instanceName in ClassicEditor.instances) { 
            ClassicEditor.instances[instanceName].updateElement();
        }
        return true;
    });
    */
    
});
EOF;
$this->registerJs($js);
?>