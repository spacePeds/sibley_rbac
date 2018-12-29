<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Link */
/* @var $form yii\widgets\ActiveForm */
$js = <<<EOF
    console.log('initing link form');
    $('#link-group').on('change', function() {
        if($(this).val() == 'new') {
            $('#newGroup').slideDown();
        } else {
            $('#newGroup').slideUp();
        }
    });
    linkTypeTest($('#link-type'));
    $('#link-type').on('change', function() {
        linkTypeTest($(this));
    });
    $('#link-pdffile').on('change',function(){
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
        $('#link-name').val(fileName);
    })
    function linkTypeTest(jqElem) {
        
        if(jqElem.val() == 'file') {
            $('#fileLinkGroup').slideDown();
            $('#link-name').slideUp();
            $('#link-pdffile').change();
        } else {
            $('#fileLinkGroup').slideUp();
            $('#link-name').slideDown();
        }
        $('#url_exHelp').hide();
        $('#url_inHelp').hide();
        if(jqElem.val() == 'xlink') {
            $('#url_exHelp').show();
        }
        if(jqElem.val() == 'ilink') {
            $('#url_inHelp').show();
        }
    }
EOF;
$this->registerJs($js);
$slOptions = [];
//echo '<pre>' . print_r($linkGroups, true) . '</pre>';
foreach($linkGroups as $group) {
    $g = $group['group'];
    $slOptions[$g] = $g;
}
$slOptions['new'] = 'Specify New Group';
?>

<div class="link-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model) ?>
        
    <?= $form->field($model, 'group',[
        'errorOptions'  => [
            'class' => 'form-control-invalid-feedback',
        ],
        'template' => '{label}<div id="newGroup" style="display:none;">
        <input type="text" name="newGroup" class="form-control" value=""></div>
        {input}
        {error}'
    ])->dropDownList($slOptions, ['prompt' => 'Please Select Link Type']) ?>


    <?= $form->field($model, 'type')->dropDownList([
        'file' => 'File', 
        'xlink' => 'External Link',
        'ilink' => 'Internal Link',
    ], ['prompt' => 'Please Select Link Type']) ?>

    <?= $form->field($model, 'name', [
        'errorOptions'  => [
            'class' => 'form-control-invalid-feedback',
        ],
        'template' => '{label}{input}
        <small id="url_exHelp" style="display:none;" class="form-text text-muted">Example: https://www.url.com</small>
        <small id="url_inHelp" style="display:none;" class="form-text text-muted">Example: /section/page</small>
        {error}'
    ])->textInput(['maxlength' => true]) ?>

    

    <?= $form->field($model, 'pdfFile', [
        'errorOptions'  => [
            'class' => 'form-control-invalid-feedback',
        ],
        'inputOptions'  => [
            'class' => 'custom-file-input',
        ],
        'labelOptions'  => [
            'class' => 'custom-file-label',
        ],
        'template' => '<div class="input-group mb-3" id="fileLinkGroup" style="display:none;">
        <div class="input-group-prepend">
            <span class="input-group-text">Upload</span>
        </div>
        <div class="custom-file">{input}{label}
        </div></div>
        {error}'
    ])->fileInput(); ?>

    <?php
        //if (!empty($model['pdfFile'])) {
            echo '<div>' . $model['pdfFile'] . '</div>';
        //}
    ?>

    <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
