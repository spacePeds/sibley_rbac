<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\SubPageAsset;

SubPageAsset::register($this);
$this->title = 'City of Sibley';
$this->params['breadcrumbs'][] = $this->title;
//echo '<pre>' . print_r($page) . '</pre>';
//echo '<pre>' . print_r($subSections) . '</pre>';

?>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=210655332287672&autoLogAppEvents=1"></script>

<!-- paralex image -->
<section id="city-heading" class="p-5">   
    <div class="row">
        <div class="col">
            <div class="container pt-5 text-left"><h2>City of Sibley</h2></div>
        </div>
    </div>
</section>

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
            
            <div class="card text-center my-3">
                <div class="card-body p-2">
                    <p class="card-text">Click on the button below to pay city utility fees. </p>
                    <a class="btn btn-primary" target="_blank" href="https://www.govpaynow.com/gps/user/cyg/plc/a001y8" role="button"><i class="far fa-credit-card"></i> Pay</a>
                </div>
                <div class="card-footer text-muted small">
                    We've partnered with GovPayNet to make paying fees easier!
                </div>
            </div>

            <div class="card text-center">
                      <div class="card-header bg-dark text-white">
                          <h4>Recent Council: <br><i>"Meetings"</i></h4> 
                      </div>
                      <div class="card-body p-0">
                          <!--<h4 class="card-title">Friday Aug 24th</h4>-->
                          
                          <ul class="list-group">
                            <?php foreach ($meetings as $meeting ): ?>
                              <li class="list-group-item">
                                <?php //echo print_r($event);
                                    echo '<h5>' . date('M jS', strtotime($meeting['date'])) .'</h5>';
                                    echo '<div class=""><i class="fas fa-check"></i> ' . $meeting['type'] . ' meeting</div>';
                                    
                                  ?>
                              </li>
                            <?php endforeach; ?>

                            
                          </ul>
                          <!--<a href="#" class="btn btn-danger btn-block mt-2">Get It</a>-->
                      </div>
                      <div class="card-footer text-muted small">
                          <a href="<?=Url::to('/sibley/council')?>">View All Available Meeting Minutes</a>
                      </div>
                  </div>

        </div>
        <div class="col-md-9">
                        
            <?= $page['body'] ?>

    
            <section class="text-center">
            <div class="fb-page" data-href="https://www.facebook.com/Sibley.Iowa" data-tabs="timeline" data-width="500" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true">
                <blockquote cite="https://www.facebook.com/Sibley.Iowa" class="fb-xfbml-parse-ignore">
                    <a href="https://www.facebook.com/Sibley.Iowa">Sibley, Iowa</a>
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

            
            <section id="elected" class="">
                <div class="container bg-white">

                    <p>Sibley's real pride is in its people. The dedicated, professional, caring individuals who create the growth and spirit of opportunity make the community a great place to live</p>

                    <?php //echo '<pre>' . print_r($staff,true) . '</pre>' ?>

                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">City Staff</h5>
                            <div class="row">
                                <?php foreach($staff as $key=>$person): ?>
                                    <?php 
                                    if ($staff[$key]['elected']) {
                                        continue;
                                    } 
                                    ?>
                                    
                                    <div class="col-md-4 mb-1">
                                        
                                        <?php if (Yii::$app->user->can('update_staff')) : ?>
                                            <div class="cardEdit">
                                                <a class="btn btn-outline-primary" href="<?= Url::to(['/staff/update/' . $staff[$key]['id']]) ?>" title="Update" aria-label="Update"><i class="fas fa-edit"></i></a>

                                                <?php if (Yii::$app->user->can('delete_staff')): ?>
                                                    <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $staff[$key]['id']], [
                                                        'class' => 'btn btn-outline-danger',
                                                        'data' => [
                                                            'confirm' => 'Are you sure you want to delete this item?',
                                                            'method' => 'post',
                                                        ],
                                                    ]) ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif;?>
                                        
                                        <div class="card h-100 text-center">
                                            
                                            <img src="<?= Yii::getAlias('@web') ?><?= isset($person['image']) ? $person['image']['path'] . $person['image']['name'] : '/img/person.png' ?>" alt="" class="card-img-top rounded-circle p-3">
                                            
                                            
                                            <div class="card-body">
                                                <h4 class="card-title"><?= $staff[$key]['first_name'] ?> <?= $staff[$key]['last_name'] ?></h4>
                                                <h6 class="card-subtitle mb-1 text-muted"><?= $staff[$key]['position'] ?></h6>
                                            </div>
                                            <div class="card-footer">
                                                Phone: <?= $staff[$key]['phone'] ?>
                                                Email: <?= $staff[$key]['email'] ?>
                                            </div>
                                        </div>

                                    </div>
                                
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <h5 class="card-title">City Council</h5>
                        <?php foreach($staff as $key=>$person): ?>
                            <?php 
                            if (!$staff[$key]['elected']) {
                                continue;
                            } 
                            ?>
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="<?= Yii::getAlias('@web') ?><?= isset($person['image']) ? $person['image']['path'] . $person['image']['name'] : '/img/person.png' ?>" alt="" class="card-img-top rounded-circle m-3 img-fluid" height="100">
                                </div>
                                <div class="col-md-5">
                                    <?php if (Yii::$app->user->can('update_staff')) : ?>
                                        <div class="staffEdit">
                                            <a class="btn btn-outline-primary" href="<?= Url::to(['/staff/update/' . $staff[$key]['id']]) ?>" title="Update" aria-label="Update"><i class="fas fa-edit"></i></a>

                                            <?php if (Yii::$app->user->can('delete_staff')): ?>
                                                <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model['id']], [
                                                    'class' => 'btn btn-outline-danger',
                                                    'data' => [
                                                        'confirm' => 'Are you sure you want to delete this item?',
                                                        'method' => 'post',
                                                    ],
                                                ]) ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif;?>
                                    <h4><?= $staff[$key]['position'] ?></h4>
                                    <h5><?= $staff[$key]['first_name'] ?> <?= $staff[$key]['last_name'] ?></h5>
                                    <div>Phone: <?= $staff[$key]['phone'] ?></div>
                                    <div>Email: <?= $staff[$key]['email'] ?></div>
                                </div>
                                <div class="col-md-5">
                                    <div class="border border-primary rounded p-3">
                                        <h5>Elected Term:</h5>
                                        <p><?= $staff[$key]['termStartFmtd']?> - <?= $staff[$key]['termEndFmtd']?></p>
                                    </div>
                                </div>
                            </div>
                        
                            
                        <?php endforeach; ?>
                    </div>
                </div>
                
            </section>

        </div>
        
    </div>

</div>
<?php if (Yii::$app->user->can('update_subPage')): ?>
<?= $this->registerJs($js); ?>
<?php endif; ?>
