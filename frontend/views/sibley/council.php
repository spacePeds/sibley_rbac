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
if (Yii::$app->user->can('update_meeting')) {
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
    console.log('meeting JS Init');
    Meeting.getBasePath('$siteRoot');
    
});
EOF;
Html::encode($script);

if (Yii::$app->user->can('update_meeting')) {
    $adminScript = <<<EOF
$(function(){   
    if (MeetingAdmin !== undefined) {
        console.log('meetingadmin exists');
        MeetingAdmin.init();
    }
});
EOF;
    Html::encode($adminScript);
}


//echo '<pre>' . print_r($model,true) . '</pre>';
?>
<?php if (Yii::$app->user->can('create_meeting')) : ?>
    <div class="adminFloater shadow-sm p-3 mb-5 bg-white rounded d-flex flex-column text-center">
        <div class="p-2"> <button id="createAgenda" class="btn btn-success">Create Agenda</button></div>
    </div>
<?php endif; ?>

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
            </div><!--end meetingmenuContainer-->
        </div>
        
        <div class="col-lg-9 mb-4">
            <h2><?= Html::encode($this->title) ?></h2>

            <div id="meetingContainer">
            
                <div class="alert alert-info" role="alert" id="instructionsContainer">
                    <h4 class="alert-heading">Please choose a meeting from the provided menu.</h4>
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