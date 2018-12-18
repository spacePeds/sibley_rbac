<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Agenda */
//echo '<pre>' . print_r($meetings,true) . '</pre>';

$lastMonth = '';
$current = date('F');
foreach($meetings as $month => $meetingsMo) {  
    $show = '';
    if ($current == $month) {
        $show = 'show';
    }
?>
    
    <div class="card">
        <div class="card-header p-0" id="heading<?= $month ?>" role="tab">
            <div class="m-1">
                
            
                <a data-toggle="collapse"  href="#" aria-expanded="true" data-target="#collapse<?= $month ?>" aria-controls="collapse<?= $month ?>" class="collapsed">
                    
                    <h5><?= $month ?></h5>
                </a>

            </div>
        </div>
        <div id="collapse<?= $month ?>" class="collapse <?=$show;?>" aria-labelledby="heading<?= $month ?>" data-parent="#meetingNavigation">
            <div class="card-body p-0">
                <div class="list-group">
                    <?php foreach ($meetingsMo as $idx => $meeting) : ?>    
                        <a class="list-group-item list-group-item-action" data-id="<?= $meeting['id'] ?>" href="#"><?= $meeting['label'] ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

<?php
}
if (count($meetings) < 1) {
    echo '<div class="card"><div class="card-header text-center">No Meetings currently posted for this year</div></div>';
}
