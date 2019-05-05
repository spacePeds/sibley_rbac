<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use frontend\assets\UploadProgressAsset;

UploadProgressAsset::register($this);

/* @var $this yii\web\View */
/* @var $upload frontend\models\ImageAsset */
/* @var $form yii\widgets\ActiveForm */

//import phone# formatting functions
$this->render('_helperFormatFileSize', []);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Asset Upload', 'url' => ''];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
$role = [];
if (isset(Yii::$app->user->identity->id)) {
    $role = \Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id);
}
$protocol = 'http://';
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    $protocol = 'https://';
}

?>
<div class="container">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="asset-form mb-4">

        <?php 
        $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data'],
            //'enableAjaxValidation'=> true,
            //'enableClientValidation' => false
        ]); ?>

        <?= $form->field($upload, 'imageFiles[]')->fileInput(['multiple'=>true, 'accept' => 'image/*', 'id' => 'fileUploader'])->hint('Click, "Choose Files" to browse one or more image on your computer to upload.') ?>

        <?= $form->errorSummary($upload) ?>

        <?= Html::error($upload, 'imageFiles'); ?>
        
        <div id="imagePreview" class="row mb-2"></div>

        <div class="form-group">
            <?php //echo Html::submitButton($upload->isNewRecord ? Yii::t('app', 'Upload') : Yii::t('app', 'Update'), ['class' => $upload->isNewRecord] ? 'btn btn-success' : 'btn btn-primary') ?>
            <?php echo Html::submitButton('Upload', ['class' => 'btn btn-success', 'id'=> 'btnUpload']); ?>
        </div>

        <?php ActiveForm::end(); ?>
        
        
    </div>

    <div class="row">
        <div id="divFiles" class="files">
        </div>
    </div>

    <div class="container">
        <h3>Available Assets</h3>

        <?php //echo '<pre>' . print_r($assets,true) . '</pre>'; ?>

        <div class="myAssets mb-3 border-secondary rounded">
            <div class="row mb-2">
                <?php 
                $count = 0;
                foreach ($assets as $asset) : 
                    $count++;               
                ?>
                    <div class="col-md-2 px-1">
                        <div class="card h-100">
                            <img src="<?= $asset['path'] ?><?= $asset['name'] ?>" alt="<?= $asset['name'] ?>" data-id="<?= $asset['id'] ?>" class="card-img-top shadow-sm mb-1 bg-white rounded">
                            <div class="card-body">
                                <div class="card-text small"><?= $asset['name'] ?> <br/>Size: <?= formatSizeUnits($asset['size']) ?></div>
                                <span class="imgLink"><div class="card-text text-truncate small">URL: <?= $protocol . $_SERVER['SERVER_NAME'] ?><?= $asset['path'] ?><?= $asset['name'] ?> </div></span>
                                <?php if (isset($role['superAdmin']) || Yii::$app->user->can('delete_asset') && $asset['created_by'] == Yii::$app->user->identity->id) : ?>
                                    <div class="card-footer">
                                        <?= Html::a(Yii::t('app', 'Delete'), ['delete2', 'id' => $asset['id']], [
                                            'class' => 'btn btn-danger btn-sm',
                                            'data' => [
                                                'confirm' => Yii::t('app', 'Are you sure you want to delete this image?'),
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($count%6 == 0): ?>
                        </div><div class="row mb-2">
                    <?php endif; ?> 
                <?php endforeach; ?>
            </div>
        </div>
    </div>
                                    </div>