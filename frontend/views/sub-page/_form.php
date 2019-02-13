<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model frontend\models\SubPage */
/* @var $form yii\widgets\ActiveForm */
$js = <<<JS
    console.log('initing sub-page form');
    //hide type dependent fields
    $('.field-subpage-body').hide();
    $('.field-subpage-path').hide();
    $('#subPage_links').hide();

    triggerType($('#subpage-type').val());

    $('#subpage-type').on('change', function() {
        triggerType($(this).val());
    });

    $(document).on('change','#Fileinput',function(){
        var imgpreview = DisplayImagePreview(this);
        $(".img_preview").show();
        var url="/sub-page/ajax-upload";
        ajaxFormSubmit(url,'#Ajaxform',function(data){
            //var data=JSON.parse(output);
            if(data.status=='success'){
                $('.im_progress').fadeOut();
                var doc = $('#img_preview').attr('src');
                console.log('doc:',doc);
                $('.All_images').append('<div class="border border-success rounded"><img class="img-thumbnail" width="100" src="'+ doc +'">'+data.label+'</div>');
                $(".img_preview").hide();
            }else{
                alert("Something went wrong.Please try again.");
                $('#uploadMessages').html('<div class="alert alert-danger">' + data.message + '</div>');
                $(".img_preview").hide();
            }
            $('#subpage-ajax_file_label').val('');
        })  
    }); 

    function triggerType(subType) {
        if(subType == 'section') {
            $('.field-subpage-body').slideDown();
            $('.field-subpage-path').slideUp();
            $('#subPage_links').slideDown();
        } else {
            $('.field-subpage-body').slideUp();
            $('.field-subpage-path').slideDown();
            $('#subPage_links').slideUp();

            if(subType == 'ilink') {
                $('#url_exHelp').hide();
                $('#url_inHelp').show();
            } else {
                $('#url_exHelp').show();
                $('#url_inHelp').hide();
            }
        }
    }

    function DisplayImagePreview(input){
        console.log(input.files);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                console.log('onload:',e);
                if (e.target.result.indexOf('application/pdf') >= 0) {
                    $('#img_preview').attr('src', '/img/pdf-placeholder.png');
                } else {
                    $('#img_preview').attr('src', e.target.result);
                }
                
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function ajaxFormSubmit(url, form, callback) {
        var formData = new FormData($(form)[0]);
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            datatype: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                // do some loading options
            },
            success: function(data) {
                console.log('callback:',data);
                callback(data);
            },
            complete: function() {
                // success alerts
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            },            
        });
    }

    

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
            $('label[for="link-name"]').text('Choose a file');
        } else {
            $('#fileLinkGroup').slideUp();
            $('#link-name').slideDown();
            $('label[for="link-name"]').text('Link');
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
JS;

?>

<div class="sub-page-form container">

    <?php $form = ActiveForm::begin([
        'id'=>'Ajaxform',
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'page_id')->hiddenInput()->label(false) ?>

    <h3><?= $model->pageLabel ?></h3>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList([
        'section' => 'Regular Section', 
        'xlink' => 'External Link',
        'ilink' => 'Internal Link',
    ], ['prompt' => 'Please Select A Section Type']) ?>

    <div class="invalidFix">
        <?= $form->field($model, 'body')->widget(CKEditor::className(), [
            'options' => ['rows' => '6'],
            'preset' => 'custom',
            'clientOptions' => [
                'toolbarGroups' => [
                    ['name' => 'basicstyles', 'groups' => [ 'basicstyles']],
                    ['name' => 'clipboard', 'groups' => [ 'clipboard', 'undo' ]],
                    ['name' => 'editing', 'groups' => [ 'spellchecker' ]],
                    ['name' => 'insert', 'groups' => [ 'insert' ]],
                    '/',               
                    ['name' => 'paragraph', 'groups' => [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ]],
                    ['name' => 'links', 'groups' => [ 'links' ]],
                    
                ],
                'removeButtons' => 'Flash,Iframe,Language,Save,NewPage'
            ]
            
        ]) ?>
        
    </div>
    
    <?= $form->field($model, 'path', [
        'errorOptions'  => [
            'class' => 'form-control-invalid-feedback',
        ],
        'template' => '{label}{input}
        <small id="url_exHelp" style="display:none;" class="form-text text-muted">Example: https://www.url.com</small>
        <small id="url_inHelp" style="display:none;" class="form-text text-muted">Example: /section/page</small>
        {error}'
    ])->textInput(['maxlength' => true]) ?>

    <div id="subPage_links" class="card mb-4">
        <div class="card-header">
            Section Attachments
            <!--<button type="button" class="float-right add-item btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</button>-->
            <div class="clearfix small text-muted">optional. Use this to attach PDFs or images to section.</div>

        </div>
        <div class="card-body container-items">
            <div class="item">
                <div class="row">
                    <div class="col-md-8">
                        <?= $form->field($model, 'ajax_file_label')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'ajax_file')->fileInput(['id'=>"Fileinput"])->label(false) ?>
                        <div id="uploadMessages"></div>
                        <div class="img_preview">
                            <div class="im_progress">
                                <img class="loader_img" src="/img/ajax-loader.gif">
                            </div>
                            <img src="" id="img_preview">
                        </div>
                    </div>
                    
                    <div class="All_images"></div>
                    
                    </div>
                    <div class="col-md-4">

                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?= $this->registerJs($js); ?>

<?php
/*
$form->field($model, 'pdfFile', [
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
                    ])->fileInput();
                    */
                    ?>
                    