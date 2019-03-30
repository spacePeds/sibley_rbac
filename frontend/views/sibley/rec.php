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

        <?php if (isset($role['superAdmin']) || (Yii::$app->user->can('update_page') && Yii::$app->user->can('update_page_rec'))): ?>
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

                <?php if (isset($role['superAdmin']) || (Yii::$app->user->can('update_page_rec') && Yii::$app->user->can('create_subPage'))): ?>
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
                            <?php //echo print_r($event);
                                echo '<h5>' . date('M jS', strtotime($meeting['date'])) .'</h5>';
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
                'adminGroup' => 'rec'            
            ]) ?>

            <?php if (isset($page['linkedOrganizations'])): ?>
                <?= $this->render('_linkedOrg', [
                    'page' => $page,               
                ]) ?>
            <?php endif; ?>
            

        </div>
        
    </div>

</div>

