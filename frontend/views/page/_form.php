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
<div class="adminFloater shadow-sm p-3 mb-5 bg-white rounded d-flex flex-column text-center">
    <?php if (Yii::$app->user->can('update_category')) : ?>
            <div class="p-2"><?= Html::a(Yii::t('app', 'Update Categories'), [Url::to('/category')], ['class' => 'btn btn-primary']) ?></div>
    <?php endif; ?>
    <?php if (Yii::$app->user->can('update_asset')) : ?>
        <div class="p-2"><?= Html::a(Yii::t('app', 'Add / Remove Image Assets'), [Url::to('multiple')], ['class' => 'btn btn-primary']) ?></div>
    <?php endif; ?>
</div>
<div class="page-form">

    <?php 
    $form = ActiveForm::begin([
        'id' => 'page-form',
        'enableAjaxValidation' => false,
    ]); ?>

    <!--<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />-->

    <?php if ($role['superAdmin']) : ?>
        <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>
    <?php else: ?>
        <?= $form->field($model, 'route')->hiddenInput()->label(false); ?>
    <?php endif; ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
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
            'removeButtons' => 'Flash,Iframe,Language,Save,NewPage'
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
    ]) ?>

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


    <div class="form-group">
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