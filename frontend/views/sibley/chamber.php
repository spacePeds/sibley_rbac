<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\SubPageAsset;

SubPageAsset::register($this);
$this->title = 'Sibley Chamber of Commerce';
$this->params['breadcrumbs'][] = $this->title;
//echo '<pre>' . print_r($page) . '</pre>';
//echo '<pre>' . print_r($subSections) . '</pre>';

?>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=106901959404215&autoLogAppEvents=1"></script>

<!-- paralex image -->

<div class="container">
    <div class="row">
        <div class="col-md-3 bg-sidebar">


            <div class="list-group my-3">
                
                <?php foreach ($subSections as $subSection): ?>
                    <?php if ($subSection['type'] == 'xlink'): ?>
                        <a href="<?= $subSection['path']?>" target="_blank" class="list-group-item list-group-item-action"><?= $subSection['title']?></a>
                    <?php else: ?>
                        <a href="<?= $subSection['path']?>" class="list-group-item list-group-item-action"><?= $subSection['title']?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (Yii::$app->user->can('create_subPage')): ?>
                    <a href="<?=Url::to('/sub-page/create')?>/<?=$key?>" class="list-group-item btn btn-outline-success btn-sm"><i class="fas fa-plus-square"></i> Create Section</a>
                <?php endif; ?>

            </div>
            
            

        </div>
        <div class="col-md-9">
                        
            <?= $page['body'] ?>

    
            <section class="text-center">
            <div class="fb-page" data-href="https://www.facebook.com/SibleyChamber" data-tabs="timeline" data-width="500" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                <blockquote cite="https://www.facebook.com/SibleyChamber" class="fb-xfbml-parse-ignore">
                    <a href="https://www.facebook.com/SibleyChamber">Sibley Iowa Chamber of Commerce</a>
                </blockquote>
            </div>
            </section>
            <?php foreach ($subSections as $subSection): ?>
                <?php if ($subSection['type'] == 'section'): ?>
                    <section id="<?=str_replace('#','',$subSection['path'])?>">
                        <?php if (Yii::$app->user->can('update_subPage')): ?>
                            <a href="<?=Url::to('/sub-page/update')?>/<?=$subSection['id']?>" class="float-right btn btn-outline-success btn-sm"><i class="fas fa-plus-square"></i> Update Section</a>
                            
                            <?= Html::a('<i class="far fa-trash-alt"></i> ' . Yii::t('app', 'Delete Section'), ['sub-page/delete', 'id' => $subSection['id']], [
                                'class' => 'float-right btn btn-outline-danger btn-sm',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this Section?'),
                                    'method' => 'post',
                                ],
                            ]) ?>
                        <?php endif; ?>
                        <h4><?= $subSection['title'] ?></h4>
                        <?= $subSection['body'] ?>
                        <?php //echo '<pre>' . print_r($subSection['documents']) . '</pre>'; ?>
                        <?php foreach ($subSection['documents'] as $document): ?>
                            <?php 
                            $path = '/'.$document['path'] . $document['name'];
                            $size = $document['size'];
                            $label = $document['label'];
                            $pos = strpos($document['type'], 'image');
                            if ($pos !== false) {
                                //image
                                ?>

                                <div data-id="<?=$document['id']?>">
                                <img class="rounded mx-auto" width="75" src="<?=$path?>">
                                <?php if (Yii::$app->user->can('update_subPage')): ?><?=$label?>
                                <a data-id="<?=$document['id']?>" class="small text-muted doDelete" href="#">Delete</a>
                                <?php endif; ?>
                                </div>

                                <?php
                            }
                            $pos = strpos($document['type'], 'pdf');
                            if ($pos !== false) {
                                //pdf 
                                ?>
                                <div data-id="<?=$document['id']?>">
                                <a role="button" class="btn btn-outline-primary mx-auto" target="_blank" href="<?=$path?>"><i class="far fa-file-pdf"></i> <?=$label?></a>
                                <?php if (Yii::$app->user->can('update_subPage')): ?>
                                <a data-id="<?=$document['id']?>" class="small text-muted doDelete" href="#">Delete</a>
                                <?php endif; ?>
                                </div>
                                <?php
                            }
                            ?>
                        <?php endforeach; ?>
                    </section>
                <?php endif; ?>
            <?php endforeach; ?>

            
            

        </div>
        
    </div>

</div>
<?php if (Yii::$app->user->can('update_subPage')): ?>
<?= $this->registerJs($js); ?>
<?php endif; ?>
