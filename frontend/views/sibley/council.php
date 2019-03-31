<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap4\Modal;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use frontend\assets\MeetingAdminAsset;
use frontend\assets\MeetingStandardAsset;
use frontend\assets\BootstrapDatepickerAsset;

$siteRoot = Url::to('@web');
if (Yii::$app->user->can('update_agenda')) {
    MeetingStandardAsset::register($this);
    MeetingAdminAsset::register($this);
    BootstrapDatepickerAsset::register($this);
} else {
    MeetingStandardAsset::register($this);
}

$this->title = 'Sibley City Council Meetings';
$this->params['breadcrumbs'][] = $this->title;

$siteRoot = ''; //Url::to('@web');
$script = <<<EOF
$(function(){
    defaultMeeting = $model->dfltAgenda;
    //initilize on load
    Meeting.getBasePath('$siteRoot');
    
});
EOF;
Html::encode($script);

if (Yii::$app->user->can('update_agenda')) {
    $adminScript = <<<EOF
$(function(){   
    if (MeetingAdmin !== undefined) {
        MeetingAdmin.init();
    }
});
EOF;
Html::encode($adminScript);
}

/*
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=106901959404215&autoLogAppEvents=1"></script>
*/
//echo '<pre>' . print_r($model,true) . '</pre>';
?>

<div class="container">
    <div class="row">
        <div class="col-lg-3 mb-4">

            <div id="meetingMenuContainer">
                
                <div class="row">
                    <div class="col-md-3 m-0">
                        <button class="btn btn-outline-secondary" id="previousYear"><i class="fas fa-angle-left"></i></button>
                    </div>
                    <div class="col-md-6 m-0">
                        <?php $form = ActiveForm::begin([ 'id' => 'meetingYearForm']); ?>
                        <?php echo $form->field($model, 'yearToggle')->dropDownList($model->yearList )->label(false); ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <div class="col-md-3 m-0">
                        <button class="btn btn-outline-secondary" id="nextYear"><i class="fas fa-angle-right"></i></button>
                    </div>
                </div>

                <div class="accordion" id="meetingNavigation" role="tablist">
                    <div class="text-center"><i class="fas fa-spinner fa-pulse"></i></div>
                </div>

                

                <?php if (Yii::$app->user->can('create_agenda')) : ?>
                    <button id="createAgenda" class="btn btn-outline-success btn-sm btn-block"><i class="fas fa-plus-square"></i> Create Agenda</button>
                <?php endif; ?>

            </div><!--end meetingmenuContainer-->
        </div>
        
        <div class="col-lg-9 mb-4">
            <h3><?= Html::encode($this->title) ?></h3>

            <div id="meetingContainer">
            
                <div class="alert alert-info" role="alert" id="instructionsContainer">
                    <h5 class="alert-heading">Please choose a meeting from the provided menu.</h5>
                </div>

            </div>
        </div>
    </div>
</div>




<?php
Modal::begin([
    'title'     => '<h2>Please Wait...</h2>',
    'id'        => 'waitModal',
    'size'      => 'modal-sm',
    
]);
echo '<div id="waitModalContent"></div>';
Modal::end();

Modal::begin([
    'title'     => '<h2></h2>',
    'id'        => 'formModal',
    'size'      => 'modal-lg',
    
]);
echo '<div id="modalContent"></div>';
Modal::end();

$this->registerJs($script);
if (Yii::$app->user->can('update_agenda')) {
    $this->registerJs($adminScript);
}