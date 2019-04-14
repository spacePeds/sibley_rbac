<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<section id="cityStaff" class="">
        <div class="container bg-white">

            <?php //echo '<pre>' . print_r($staff,true) . '</pre>' ?>

            <div class="card border-light my-2">
                <div class="card-body p-0">
                    <div class="clearfix">
                        <?php if (Yii::$app->user->can('create_staff')): ?>
                            <?= Html::a('Create Staff', ['/staff/create'], ['class' => 'btn btn-success float-right']) ?>
                        <?php endif;?>
                        <h4 class="card-title">City Staff</h4>
                    </div>
                    
                    <div class="row">
                        <?php foreach($staff as $key=>$person): ?>
                            <?php 
                            //if ($staff[$key]['elected']) {
                            //    continue;
                            //} 
                            ?>
                            
                            <div class="col-md-4 mb-1">
                                
                                <?php if (Yii::$app->user->can('update_staff')) : ?>
                                    <div class="cardEdit">
                                        <a class="btn btn-outline-primary" href="<?= Url::to(['/staff/update/' . $staff[$key]['id']]) ?>" title="Update" aria-label="Update"><i class="fas fa-edit"></i></a>

                                        <?php if (Yii::$app->user->can('delete_staff')): ?>
                                            <?= Html::a('<i class="fas fa-trash"></i>', ['/staff/delete', 'id' => $staff[$key]['id']], [
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
                                    
                                    <img src="<?= Yii::getAlias('@web') ?><?= isset($person['image']) ? '/'. Yii::$app->params['staffImagePath'] . $person['image'] : '/img/person.png' ?>" alt="" class="card-img-top rounded-circle p-3">
                                    
                                    
                                    <div class="card-body">
                                        <h4 class="card-title"><?= $staff[$key]['first_name'] ?> <?= $staff[$key]['last_name'] ?></h4>
                                        <h6 class="card-subtitle mb-1 text-muted"><?= $staff[$key]['position'] ?></h6>
                                    </div>
                                    <div class="card-footer p-2">
                                        <?php if (!empty($staff[$key]['phone'])): ?>
                                            <div class="small">Phone: <?= format_phone('us',$staff[$key]['phone']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($staff[$key]['email'])): ?>
                                            <div class="small">Email: <?= $staff[$key]['email'] ?></div>
                                        <?php endif; ?>
                                        <?php if ($staff[$key]['elected']): ?>
                                            <p class="small">Term: <?= $staff[$key]['termStartFmtd']?> - <?= $staff[$key]['termEndFmtd']?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div>
                        
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </section>