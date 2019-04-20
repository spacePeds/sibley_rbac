<?php
use yii\helpers\Url;

$role = [];
if (isset(Yii::$app->user->identity->id)) {
    $role = \Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id);
}
if (isset($page['headerImages']['parallax'])) {
    echo $this->render('_paralax', ['images'=>$page['headerImages']['parallax']]);
}

?>
<div class="container">
    <div class="row">
        <div class="col-md-3 bg-sidebar">

        <?php if (isset($role['superAdmin']) ||  Yii::$app->user->can('update_page'.$page['adminKey'])): ?>
        
            <div class="list-group my-3">
                <a href="<?= Url::to(['/page/update']) . '/'.$page['id'] ?>" role="button" class="btn btn-primary">Edit Page</a>
            </div>
        <?php endif; ?>

        <div class="list-group my-3" id="sortable" data-page="<?=$page['id']?>">

            <?php //if (!empty($page['fb_token']) && !empty($page['fb_link'])): ?>
                <!--<a href="#facebook" class="list-group-item list-group-item-action">Facebook Feed</a>-->
            <?php //endif; ?>

            <?php if(!empty($page['staff'])): ?>
                <a href="#cityStaff" class="list-group-item list-group-item-action">City Staff</a>
            <?php endif;?>
            
            <?php foreach ($page['subSections'] as $subSection): ?>
                <?php if ($subSection['type'] == 'xlink'): ?>
                    <a href="<?= $subSection['path']?>" target="_blank" id="<?=$subSection['id']?>_<?=$subSection['sort_order']?>" class="list-group-item list-group-item-action">
                    <?= $subSection['title']?>
                    <?php if(Yii::$app->user->can('update_page'.$page['adminKey'])): ?>
                        <span class="badge badge-light float-right handle border border-secondary"><i class="fas fa-bars"></i></span>
                        <span class="sr-only">Sort <?=$subSection['sort_order']?></span></a>
                    <?php endif ?>
                <?php else: ?>
                    <a href="<?= $subSection['path']?>" id="<?=$subSection['id']?>_<?=$subSection['sort_order']?>" class="list-group-item list-group-item-action">
                    <?= $subSection['title']?>
                    <?php if(Yii::$app->user->can('update_page'.$page['adminKey'])): ?>
                        <span class="badge badge-light float-right handle border border-secondary"><i class="fas fa-bars"></i></span>
                        <span class="sr-only">Sort <?=$subSection['sort_order']?></span></a>
                    <?php endif ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if (isset($role['superAdmin']) || (Yii::$app->user->can('update_page'.$page['adminKey']) && Yii::$app->user->can('create_subPage'))): ?>
                <a href="<?=Url::to('/sub-page/create')?>/<?=$page['id']?>" class="list-group-item btn btn-outline-success btn-sm"><i class="fas fa-plus-square"></i> Create Section</a>
            <?php endif; ?>

        </div>

        <?php if (count($page['govPayNet']) > 0): ?>
            <?= $this->render('_govPayNetWidget', [
                'text' => $page['govPayNet']['description'], 
                'link' => $page['govPayNet']['link']
            ]) ?>
        <?php endif; ?> 

        <?php if (strpos($page['route'],'recreation') !== false): ?>
            
            

            <div class="card text-center">
                <div class="card-header bg-dark text-white">
                    <h4>This Week: <br><i>"At the Rec"</i></h4> 
                </div>
                <div class="card-body p-0">
                    <!--<h4 class="card-title">Friday Aug 24th</h4>-->
                    
                    <ul class="list-group">
                        <?php foreach ($page['events'] as $event ): ?>
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

            

            <div class="card text-center">
                <div class="card-header bg-dark text-white">
                    <h4>Recent Council: <br><i>"Meetings"</i></h4> 
                </div>
                <div class="card-body p-0">
                    <!--<h4 class="card-title">Friday Aug 24th</h4>-->
                    
                    <ul class="list-group">
                    <?php foreach ($page['meetings'] as $meeting ): ?>
                        <li class="list-group-item">
                        <?php //echo print_r($meeting,false);
                            echo '<h5>' . $meeting['fmtdDt'] .'</h5>';
                            echo '<div class=""><i class="far fa-handshake"></i> <a href="'.Url::to('/sibley/council/'.$meeting['id']).'">' . $meeting['type'] . ' meeting</a></div>';
                            
                            ?>
                        </li>
                    <?php endforeach; ?>
                    <?php if (count($page['meetings']) < 1): ?>
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
                                    
            <?php 
            if (isset($page['headerImages']['normal']) || isset($page['headerImages']['rounded'])) {
                echo $this->render('_standardImage', ['headerImages'=>$page['headerImages']]);
            }
            ?>
            <?= $page['body'] ?>

            <?php if (count($page['staff']) > 0): ?>
                <?= $this->render('_cityStaff', [
                    'staff' => $page['staff'],           
                ]) ?>
            <?php endif; ?>

            <?= $this->render('_subSectionView', [
                'subSections' => $page['subSections'],
                'adminGroup' => $page['adminKey'],
                'facebook' => ['fb_link' => $page['fb_link'],'title' => $page['title']]
            ]) ?>

            <?php if (isset($page['linkedOrganizations'])): ?>
                <?= $this->render('_linkedOrg', [
                    'page' => $page,               
                ]) ?>
            <?php endif; ?>
            

        </div>
        
    </div>

</div>