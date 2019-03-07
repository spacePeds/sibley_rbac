<?php

use yii\helpers\Html;


$startDt = $jsVar['startDt'];
$endDt = $jsVar['endDt'];
$notes = $jsVar['notes'];
$location = $jsVar['location'];
$rrule = $jsVar['rrule'];
$desc = $jsVar['description'];
$desc = str_replace(array("\r", "\n"), '', $desc);
$js = <<<JS
var icsSubject = '$model->subject';
var icsStartDt = "$startDt";
var icsEndDt = "$endDt";
var icsLocation = "$location";
var icsRRule = $rrule;
var icsDescription = "$desc";
JS;
$this->registerJs($js);
?>
<div class="event-view" data-subject="<?= $model->subject ?>">

    <?php //echo '<pre>' . print_r($model,true) . '</pre>'; ?>
    <?php //echo '<pre>' . print_r($jsVar,true) . '</pre>'; ?>

    <?= !empty($model->group) ? '<div class="text-right font-weight-light small">Posted by: ' . $model->group . '</div>' : '' ?> 
    <p><?= $model->start_dt ?></p>
    <div class="container">
        <?= !empty($model->notes) ? '<div>' . $model->notes . '</div>' : '' ?>

        <?= $model->description ?>

        <div class="row">
            <div class="col-md-6">
                <?php if (!empty($model->pdfFile)) : ?>
                    <ul>
                        <li><a href="<?= $model->pdfFile ?>"><i class="far fa-file-pdf"></i>&nbsp;<?= $model->pdfFileName ?></a></li>
                        <!--<li>http://localhost/sibley_rbac/frontend/web/media/20181111181141_keyboard-shortcuts-windows.pdf</li>-->
                    </ul>
                <?php endif; ?>
            </div>
            <div class="col-md-6 text-right">
            
                <a class="icsLink" href="#"><i class="far fa-calendar-plus"></i> Add</a>
            </div>
        </div>
        
    </div>
    

</div>
