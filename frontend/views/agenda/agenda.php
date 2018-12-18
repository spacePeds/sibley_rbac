<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Agenda */
//echo '<pre>' . print_r($agenda,true) . '</pre>';

?>
<div class="tabs">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
        <a class="nav-link active" id="agenda-tab" data-toggle="tab" href="#agenda" role="tab" aria-controls="agenda" aria-selected="true">Agenda</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" id="minutes-tab" data-toggle="tab" href="#minutes" role="tab" aria-controls="minutes" aria-selected="false">Minutes</a>
        </li>
        
    </ul>
  
 
    <div class="card tab-content border-top-0 ">
        <div class="tab-pane fade show active" id="agenda" role="tabpanel" aria-labelledby="agenda-tab">
        
            <div class="card-body">
                <span class="float-right small"><?=ucfirst($agenda['type'])?> Meeting</br>Created: <?=date("m/d/Y", strtotime($agenda['aCreateDt']))?></span>
                <h4>Agenda: <?=date('l F jS Y',strtotime($agenda['date']))?></h4>
                <div class="card-text"><?=$agenda['aBody']?></div>
            </div>
            <?php if (Yii::$app->user->can('update_meeting')) : ?>
                <div class="card-footer text-right">
                    <?php if (Yii::$app->user->can('delete_meeting')) : ?>
                        <a href="<?= Url::to(['/agenda/delete']) .'/' . $agenda['aId'] ?>" role="button" class="btn btn-outline-danger" data-confirm="Are you sure you want to delete this agenda? Any associated minutes will also be deleted!" data-method="post">Delete</a>
                        
                    <?php endif; ?>
                    <button id="editAgenda" data-id="<?=$agenda['aId']?>" class="btn btn-success">Edit Agenda</button>
                </div>
            <?php endif; ?>

        </div>
        <div class="tab-pane fade" id="minutes" role="tabpanel" aria-labelledby="minutes-tab">
            
            <div class="card-body">
                
                <?php if(empty($agenda['mId'])): ?>

                    <div class="alert alert-secondary clearfix" role="alert">
                        
                        <?php if (Yii::$app->user->can('create_minutes')) : ?>
                            <span class="float-right">
                                <button id="createMinutes" data-agenda="<?=$agenda['aId']?>" data-date="<?=date('l F jS',strtotime($agenda['date']))?>" class="btn btn-success">Create Minutes</button>
                            </span>    
                        <?php endif; ?>
                        
                        <p class="card-text">Minutes are currently unavailable for this meeting.</p> 
                    </div>
            
                <?php else: ?>
                    <div class="card-body">
                        <span class="float-right small">
                            Created: <?=date("m/d/Y", strtotime($agenda['mCreateDt']))?>
                        </span>
                        <h5 class="card-title">Minutes: <?=date('l F jS Y',strtotime($agenda['date']))?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">Council Members attending: <?=$agenda['attend']?></h6>
                        <h6 class="card-subtitle mb-2 text-muted">Council Members absent: <?=$agenda['absent']?></h6>
                        
                        <div class="card-text"><?=$agenda['mBody']?></div>
                    </div>

                    <?php if (Yii::$app->user->can('update_minutes')) : ?>
                        <div class="card-footer text-right">
                            <?php if (Yii::$app->user->can('delete_minutes')) : ?>
                                <a href="<?= Url::to(['/agenda-minutes/delete']) .'/' . $agenda['mId'] ?>" role="button" class="btn btn-outline-danger" data-confirm="Are you sure you want to delete these minutes?" data-method="post">Delete</a>
                        
                            <?php endif; ?>
                            <button id="editMinutes" data-id="<?=$agenda['mId']?>" data-date="<?=date('l F jS',strtotime($agenda['date']))?>" class="btn btn-success">Edit Minutes</button>
                        </div>
                    <?php endif; ?>
            
                <?php endif; ?>

            </div>
        </div>
    </div>
<?php
/*  */
?>

</div>



