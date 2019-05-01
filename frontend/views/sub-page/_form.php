<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use frontend\assets\SubPageAsset;

SubPageAsset::register($this);

/* @var $this yii\web\View */
/* @var $model frontend\models\SubPage */
/* @var $form yii\widgets\ActiveForm */
?>

<div id="sub-page-form" class="container">

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
        <small id="url_fbHelp" style="display:none;" class="form-text text-muted">Please Review: https://developers.facebook.com/docs/plugins/page-plugin/. Or ask your web developer for assistance.</small>
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
                    <div class="col-md-8 border rounded">
                        <?= $form->field($model, 'ajax_file_label')->textInput(['maxlength' => true]) ?>
                        <div class="row">
                            <div class="col">
                                <?= $form->field($model, 'ajax_file')->fileInput(['id'=>"Fileinput"])->label(false) ?>
                                <div id="uploadMessages"></div>
                                <div class="img_preview">
                                    <div class="im_progress" style="display:none;">
                                        <img class="loader_img" src="/img/ajax-loader.gif">
                                    </div>
                                    <img src="" id="img_preview">
                                </div>
                            </div>
                            <div class="col text-right">
                                <button type="button" id="attachUploadButn" class="btn btn-success" disabled><i class="fas fa-upload"></i> Upload</button>
                            </div>
                        </div>
                        

                        
                    </div>
                    
                    
                    
                    <div class="col-md-4">
                        
                        <div class="All_images card">
                            <ul class="list-group list-group-flush">
                                <?php foreach ($documents as $document): ?>
                                    <?php 
                                    $path = '/'.$document['path'] . $document['name'];
                                    $size = $document['size'];
                                    $label = $document['label'];
                                    $pos = strpos($document['type'], 'image');
                                    if ($pos !== false) {
                                        //image
                                        ?>
                                        <li class="list-group-item p-1">
                                            <div data-id="<?=$document['id']?>" class="">
                                                
                                                <span class="badge badge-warning float-right"><a data-id="<?=$document['id']?>" data-confirm="Are you sure you wish to delete this image?" class="text-muted doDelete" href="#">Delete</a></span>
                                                <img class="rounded mx-auto" width="75" src="<?=$path?>"> <?=$label?>
                                                
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    $pos = strpos($document['type'], 'pdf');
                                    if ($pos !== false) {
                                        //pdf 
                                        ?>
                                        <li class="list-group-item p-1">
                                            <div data-id="<?=$document['id']?>">
                                                <span class="badge badge-warning float-right"><a data-id="<?=$document['id']?>" data-confirm="Are you sure you wish to delete this PDF?" class="text-muted doDelete" href="#">Delete</a></span>
                                                <a role="button" class="btn btn-outline-primary mx-auto" target="_blank" href="<?=$path?>"><i class="far fa-file-pdf"></i> <?=$label?></a>                                            
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group text-right mr-3">
        <button type="button" id="cancelButn" class="btn btn-link">Cancel</button>
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

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
                    