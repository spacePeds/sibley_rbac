<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\SiteAlert */
?>
<div id="Container-<?=$group?>">
    
    <div class="slick row align-items-start">
        <div class="col-2 p-1">
            <h3 class="modHeader m-2"><?=$title?></h3>
        </div>
        <div class="col-8 p-1">
            <div class="SlickCarousel-<?=$group?>"> 
                
                <?php foreach ($alerts as $type => $alert): ?>
                    <div class="alert alert-<?=$type?> p-1 mt-2" role="alert" data-type="<?=$type?>">
                        <div><?=$alert['title']?>&nbsp;
                            <?php if (Yii::$app->user->can('update_alert')): ?>
                                <a href="/alert/update/<?= $alert['id']?>" title="Update" aria-label="Update" class="float-right"><i class="fas fa-edit"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-2 p-1">
            <div class="Arrows"><span class="arrow"></span><span class="badge badge-dark m-2 py-2">New</span></div>
            
        </div>  
    </div>
    
    <!-- Carousel Container -->
    <div class="row slider-for-<?=$group?>">
        <?php foreach ($alerts as $type => $alert): ?>
        <?php $display = (empty($alert['message'])) ? '' : 'class="alert alert-'.$type.'" role="alert"' ?>
        <!-- Item -->
        <div class="col">
            <div class="container">               
                <div <?=$display?>><?=$alert['message']?></div>
            </div>
        </div>
        <!-- Item -->

        <?php endforeach; ?>
    </div>
    <!-- Carousel Container -->
</div>