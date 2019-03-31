<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\SubPageAsset;

if (Yii::$app->user->can('update_subPage')) {
    SubPageAsset::register($this); 
}

$this->title = $page['title'];
$this->params['breadcrumbs'][] = $this->title;
//echo '<pre>' . print_r($page) . '</pre>';
//echo '<pre>' . print_r($subSections) . '</pre>';
$standardHeaderImages = [];

$user_id = Yii::$app->user->identity->id;
$role = \Yii::$app->authManager->getRolesByUser($user_id);

//import phone# formatting functions
$this->render('_helperFormatPhone', []);

if (!empty($page['fb_token'])):
?>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=<?=$page['fb_token']?>&autoLogAppEvents=1"></script>
<?php endif; ?>

<!-- paralex image -->
<?php if (isset($page['headerImages'])): ?>
    <?php foreach ($page['headerImages'] as $headerImage) {
        $imgPath = $headerImage['image_path'];
        $height = !empty($headerImage['height']) ? $headerImage['height'] : '';
        $offset = !empty($headerImage['offset']) ? $headerImage['offset'] : '';
        $class = 'float-left';
        if ($headerImage['display'] == 'rounded') {
            $class = 'rounded-circle';
        }
        if (!empty($headerImage['position'])) {
            if ($headerImage['position'] == 'center') {
                $class .= ' mx-auto d-block';
            }
            if ($headerImage['position'] == 'right') {
                $class .= ' float-right';
            }
        }
        if ($headerImage['display'] == 'parallax') {
            $style = 'min-height:'.$headerImage['height'].'px;';
            $style .= "background: url('".$imgPath."');";
            $style .= 'background-position:center;background-size: cover;'; //background-position:'.$offset.',0
            $style .= 'text-align:center;color:#fff;position: relative;background-attachment: fixed;background-repeat: no-repeat;';
            ?>
            <section class="p-3" style="<?=$style?>">
                <div class="parallax-overlay" style="background: rgba(0,0,0,<?=$headerImage['brightness']?>);">
                    <div class="row">
                        <div class="col">
                            <div class="container pt-5">
                        </div>
                    </div>
                    </div>
                </div>
            </section>
            <?php
        }
    
        if ($headerImage['display'] != 'parallax') {
            if (!empty($offset)) {
                $style = "margin-top:".$offset."px;";
            }
            $standardHeaderImages[] = '<img src="'.$imgPath.'" height="'.$height.'" class="'.$class.'" style="'.$style.'">';       
        }
    }
    ?>
<?php endif; ?>

<div class="container">
    <div class="row">
        <div class="col-md-3 bg-sidebar">

            <?php if (isset($role['superAdmin']) || (Yii::$app->user->can('update_page') && Yii::$app->user->can('update_page_city'))): ?>
                <div class="list-group my-3">
                    <a href="<?= Url::to(['/page/update']) . '/'.$key ?>" role="button" class="btn btn-primary">Edit Page</a>
                </div>
            <?php endif; ?>

            <div class="list-group my-3">

                <?php if (!empty($page['fb_token']) && !empty($page['fb_link'])): ?>
                    <a href="#facebook" class="list-group-item list-group-item-action">Facebook Feed</a>
                <?php endif; ?>

                <?php if(isset($staff)): ?>
                    <a href="#cityStaff" class="list-group-item list-group-item-action">City Staff</a>
                <?php endif;?>
                
                <?php foreach ($subSections as $subSection): ?>
                    <?php if ($subSection['type'] == 'xlink'): ?>
                        <a href="<?= $subSection['path']?>" target="_blank" class="list-group-item list-group-item-action"><?= $subSection['title']?></a>
                    <?php else: ?>
                        <a href="<?= $subSection['path']?>" class="list-group-item list-group-item-action"><?= $subSection['title']?></a>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if (isset($role['superAdmin']) || (Yii::$app->user->can('update_page_city') && Yii::$app->user->can('create_subPage'))): ?>
                    <a href="<?=Url::to('/sub-page/create')?>/<?=$key?>" class="list-group-item btn btn-outline-success btn-sm"><i class="fas fa-plus-square"></i> Create Section</a>
                <?php endif; ?>

            </div>
            
            <?php if (strpos($page['route'],'recreation') !== false): ?>
            
                <?= $this->render('_govPayNetWidget', [
                    'text' => 'Click on the button below to pay program and rental fees, or to purchase a pool pass.', 
                    'link' => 'https://www.govpaynow.com/gps/user/plc/a001y7'
                ]) ?>
            
                <div class="card text-center">
                    <div class="card-header bg-dark text-white">
                        <h4>This Week: <br><i>"At the Rec"</i></h4> 
                    </div>
                    <div class="card-body p-0">
                        <!--<h4 class="card-title">Friday Aug 24th</h4>-->
                        
                        <ul class="list-group">
                            <?php foreach ($events as $event ): ?>
                            <li class="list-group-item">
                                <?php //echo print_r($event);
                                    echo '<h5>' . date('M jS', strtotime($event['start_dt'])) .'</h5>';
                                    echo '<div class=""><i class="fas fa-check"></i> ' . $event['subject'] . '</div>';
                                    if (!$event['all_day'])  {                                      
                                        echo '<div class="small muted">' . date('g:ia', strtotime($event['start_dt'])) . '</div>';
                                    } 
                                ?>
                            </li>
                            <?php endforeach; ?>

                            
                        </ul>
                        <!--<a href="#" class="btn btn-danger btn-block mt-2">Get It</a>-->
                    </div>
                    <div class="card-footer text-muted small">
                        <a href="<?=Url::to('/sibley/calendar')?>">View Full Calendar</a>
                    </div>
                </div>

            <?php endif; ?>

            <?php if (strpos($page['route'],'city') !== false): ?>

                <?= $this->render('_govPayNetWidget', [
                    'text' => 'Click on the button below to pay city utility fees.', 
                    'link' => 'https://www.govpaynow.com/gps/user/cyg/plc/a001y8'
                ]) ?>

                

                <div class="card text-center">
                    <div class="card-header bg-dark text-white">
                        <h4>Recent Council: <br><i>"Meetings"</i></h4> 
                    </div>
                    <div class="card-body p-0">
                        <!--<h4 class="card-title">Friday Aug 24th</h4>-->
                        
                        <ul class="list-group">
                        <?php foreach ($meetings as $meeting ): ?>
                            <li class="list-group-item">
                            <?php //echo print_r($meetings);
                                echo '<h5>' . $meeting['fmtdDt'] .'</h5>';
                                echo '<div class=""><i class="fas fa-check"></i> ' . $meeting['type'] . ' meeting</div>';
                                
                                ?>
                            </li>
                        <?php endforeach; ?>
                        <?php if (count($meetings) < 1): ?>
                            <li class="list-group-item text-muted small">Recent meeting data unavailable</li>
                        <?php endif; ?>
                        
                        </ul>
                        <!--<a href="#" class="btn btn-danger btn-block mt-2">Get It</a>-->
                    </div>
                    <div class="card-footer text-muted small">
                        <a href="<?=Url::to('/sibley/council')?>">View All Available Meeting Minutes</a>
                    </div>
                </div>
            
            <?php endif; ?>

        </div>
        <div class="col-md-9">
                        
            <?php foreach ($standardHeaderImages as $leadImage) {
                echo $leadImage;
            }
            ?>
            <?= $page['body'] ?>

            <?php if (!empty($page['fb_token']) && !empty($page['fb_link'])): ?>
                <section class="text-center" id="facebook">
                <h4 class="text-left"><?=$page['title']?> Facebook Feed</h4>
                <div class="fb-page" data-href="https://www.facebook.com/<?=$page['fb_link']?>" data-tabs="timeline" data-width="500" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                    <blockquote cite="https://www.facebook.com/<?=$page['fb_link']?>" class="fb-xfbml-parse-ignore">
                        <a href="https://www.facebook.com/<?=$page['fb_link']?>"><?=$page['title']?></a>
                    </blockquote>
                </div>
                </section>
            <?php endif; ?>

            <?= $this->render('_subSectionView', [
                'subSections' => $subSections,
                'adminGroup' => 'city'            
            ]) ?>

            
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

            <?php if (isset($page['linkedOrganizations'])): ?>
                <?= $this->render('_linkedOrg', [
                    'page' => $page,               
                ]) ?>
            <?php endif; ?>
        </div>
        
    </div>

</div>

<?php
/*
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
*/
?>