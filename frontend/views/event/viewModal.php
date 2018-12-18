<?php

use yii\helpers\Html;

?>
<div class="event-view">

<?php //echo '<pre>' . print_r($model,true) . '</pre>'; ?>
<?php //echo '<pre>' . print_r($pdf,true) . '</pre>'; ?>

<div class="card">
    <div class="card-header">
        <h4 class="card-title"><i class="far fa-calendar-alt"></i> <?= $model->subject ?></h4>
    </div>
    <div class="card-body">
        <?= !empty($model->group) ? '<div class="float-right font-weight-light small">Posted by: ' . $model->group . '</div>' : '' ?>    
        
        <p class="card-text clearfix"><?= $model->start_dt ?></p>
        <?= !empty($model->notes) ? '<div>' . $model->notes . '</div>' : '' ?>
        
        
        <div class="container">
            <div>
                <?= $model->description ?>
            </div>

            
        </div>
    
    </div> 
    <div class="card-footer">
        <?php if (!empty($model->pdfFile)) : ?>
            <ul>
                <li><a href="<?= $model->pdfFile ?>"><i class="far fa-file-pdf"></i>&nbsp;<?= $model->pdfFileName ?></a></li>
                <!--<li>http://localhost/sibley_rbac/frontend/web/media/20181111181141_keyboard-shortcuts-windows.pdf</li>-->
            </ul>
        <?php endif; ?>
    </div>
</div>

</div>
