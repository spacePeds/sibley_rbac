<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use frontend\assets\BootstrapDatepickerAsset;
use frontend\assets\StaffAsset;
use yii\helpers\Url;

StaffAsset::register($this);
BootstrapDatepickerAsset::register($this);
/* @var $this yii\web\View */
/* @var $model common\models\Staff */
/* @var $form yii\widgets\ActiveForm */

$js = <<<EOF
Staff.init();
EOF;
$this->registerJs($js);

$staffImage = '';
if (!empty($model->image)) {
    $path = '/'. Yii::$app->params['staffImagePath'] . $model->image;
    $staffImage = '<img src="'.$path.'" class="img-thumbnail">';
}
                                            
if (isset($staffImg)) {
    $staffImage = $staffImg;
}

?>

<div class="container staff-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'position')->dropDownList([
                'mayor' => 'Mayor', 
                'council' => 'City Council',
                'clerk' => 'City Clerk',
                'administrator' => 'City Administrator',
                'staff' => 'Staff'
            ], ['prompt' => '']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'elected')->dropDownList([ 1 => 'Yes', 0 => 'No', ], ['prompt' => '']) ?>
        </div>
    </div>

    <div id="electedElements">

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($elected, 'term_start', [
                    'errorOptions'  => [
                        'class' => 'form-control-invalid-feedback',
                    ],
                    'template' => '{label}<div class="input-group date">{input}<div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="button-term_start"><i class="far fa-calendar-alt"></i></button>
                        </div></div><small id="term_startHelpBlock" class="form-text text-muted">Click on the calendar icon to choose a date</small>{error}'
                ])->textInput(['placeholder' => 'MM/DD/YYYY', 'aria-describedby' => 'term_startHelpBlock']) ?>
            </div>
        
            <div class="col-md-6">
                <?= $form->field($elected, 'term_end', [
                    'errorOptions'  => [
                        'class' => 'form-control-invalid-feedback',
                    ],
                    'template' => '{label}<div class="input-group date">{input}<div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="button-term_end"><i class="far fa-calendar-alt"></i></button>
                        </div></div><small id="term_endHelpBlock" class="form-text text-muted">Click on the calendar icon to choose a date</small>{error}'
                ])->textInput(['placeholder' => 'MM/DD/YYYY', 'aria-describedby' => 'term_endHelpBlock']) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

                    <pre><?php //echo var_dump($imgAssets) ?></pre>

    
    <div class="row"><div class="col-md-2">
        <div id="staffImgContainer"><?=$staffImage?></div>
    </div></div>

    
    <?= $form->field($model, 'imageFile', [
        'errorOptions'  => [
            'class' => 'form-control-invalid-feedback',
        ],
        'inputOptions'  => [
            'class' => 'custom-file-input',
        ],
        'labelOptions'  => [
            'class' => 'custom-file-label',
        ],
        'template' => '<div class="input-group mb-3" id="fileLinkGroup">
        <div class="input-group-prepend">
            <span class="input-group-text">Upload</span>
        </div>
        <div class="custom-file">{input}{label}
        </div></div>
        {error}'
    ])->fileInput(); ?>


    <div class="form-group text-right">
        <button type="button" id="cancelButn" class="btn btn-link">Cancel</button>
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
/*
<button id="imgAssetToggle" type="button" class="btn btn-secondary btn-sm">View available Images</button>
    
    <div id="imgAssetContainer" class="m-3 p-3 border border-secondary rounded clearfix" style="display:none;">
        <div class="row staffImg">
            <div class="col-md-12">
                <div id="staffCarousel" class="carousel slide" data-ride="carousel">

                    <ol class="carousel-indicators">
                        <li data-target="#staffCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#staffCarousel" data-slide-to="1"></li>
                    </ol>

                    <!-- Carousel items -->
                    <div class="carousel-inner">

                        <?php 
                        $first = true;
                        foreach ($imgAssets as $idx => $imgAsset) {  
                            if ($idx%4 == 0 && !$first) {
                                echo '</div>';
                                echo '<!--.row-->';
                                echo '</div>';
                                echo '<div class="carousel-item">';
                                echo '<div class="row">';
                            }
                            if ($first) {
                                echo '<div class="carousel-item active">';
                                echo '<div class="row">';
                                $first = false;
                            }
                            ?>
                            <div class="col-md-3">
                                <a href="#">
                                    <img data-id="<?=$imgAsset['id']?>" src="<?= Yii::getAlias('@web') ?><?=$imgAsset['path']?><?=$imgAsset['name']?>" alt="Image" style="max-width:100%;">
                                </a>
                            </div>
                        <?php    
                        } 
                        ?>
                        </div>
                        </div>

                    </div>
                    <!--.carousel-inner-->

                    <a class="carousel-control-prev" href="#staffCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                    </a>

                    <a class="carousel-control-next" href="#staffCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                    </a>

                </div>
                <!--.Carousel-->

            </div>
        </div>
    </div>
*/
?>