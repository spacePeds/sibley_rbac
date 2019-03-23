<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\SubPageAsset;

$this->title = 'Sibley Recreation Department';
$this->params['breadcrumbs'][] = $this->title;
//echo '<pre>' . print_r($page) . '</pre>';
//echo '<pre>' . print_r($subSections) . '</pre>';

?>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=106901959404215&autoLogAppEvents=1"></script>
<!-- paralex calendar -->
<section id="rec-heading" class="p-5">    
    <div class="row">
        <div class="col">
            <div class="container text-right headerTextShadow"><h2>Sibley Recreation Department</h2></div>
        </div>
    </div>
</section>

<div class="container">
    <div class="row">
        <div class="col-md-3 bg-sidebar">


            <div class="list-group my-3">
                <a href="#membership" class="list-group-item list-group-item-action">Membership</a>
                <a href="sibley/calendar" class="list-group-item list-group-item-action">Calendar of Events</a>
                <a href="#" class="list-group-item list-group-item-action">Current Programs</a>
                <a href="#" class="list-group-item list-group-item-action">Image Gallery</a>
                <a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">Get Involved</a>
                <a href="#contact" class="list-group-item list-group-item-action">Contact</a>
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
                    <p class="card-text">Click on the button below to pay program and rental fees, or to purchase a pool pass. </p>
                    <a class="btn btn-primary" target="_blank" href="https://www.govpaynow.com/gps/user/plc/a001y7" role="button"><i class="far fa-credit-card"></i> Pay</a>
                </div>
                <div class="card-footer text-muted small">
                    We've partnered with GovPayNet to make paying fees easier!
                </div>
            </div>

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

        </div>
        <div class="col-md-9">
            <div class="row">
                
                <div class="col-md-6">
                    <h1>What we do</h1>
                    <p>The Sibley Recreation Department offers positive and exciting programs for kids starting at age 4, to adult programming. 
                        We strive to provide affordable activities year round to the community. There are a wide range of programs for all ages 
                        and abilities. We encourage everyone to get involved and enjoy the many programs Sibley Rec provides!</p>
                </div>
                <div class="col-md-6">
                    <img src="../img/assets/1547328212515.jpg" alt="" class="rec-img img-fluid rounded-circle d-none d-md-block featured-img">
                </div>
            </div>
            
            <?= $page['body'] ?>
            
            <section class="text-center">
                <div class="fb-page" data-href="https://www.facebook.com/Sibley-Parks-Recreation-Department-215301921867637" data-tabs="timeline" data-width="500" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                <blockquote cite="https://www.facebook.com/Sibley-Parks-Recreation-Department-215301921867637" class="fb-xfbml-parse-ignore">
                <a href="https://www.facebook.com/Sibley-Parks-Recreation-Department-215301921867637">Sibley Parks &amp; Recreation Department</a>
                </blockquote></div>
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

            <section id="membership">
                <h4>Memberships</h4>
                <h5>Elementary Gym Membership</h5>
                <p>The Sibley-Ocheyedan Elementary Gym, located at 416 9th Ave. is a great place to get in shape all year around. Any time that school 
                    is not in session and there isn’t a Recreation Program going on, members can bring their families in to walk, shoot baskets, or just 
                    run around and play in the gym. Membership keys are available for purchase at the Sibley-Ocheyedan High Schools Superintendent’s Office.</p>
                
                <p>The membership fee is $60.00 per year. Call the Sibley-Ocheyedan High Superintendent’s at 754-2533 for more information about getting your family a membership.</p>

                <p>Roles:</p>
                <ol>
                    <li>It is recommended that adults receive doctor’s permission before using the facility.</li>
                    <li>Absolutely NO SMOKING on school property.</li>
                    <li>No food or drink (other than water) allowed in the Franklin Gym.</li>
                    <li>You will not allow anyone outside your immediate family the use of your key or it can be taken away.</li>
                    <li>You must be at least a HS freshman to use this facility, otherwise adult supervision is required.</li>
                    <li>NO DUNKING!!</li>
                    <li>Respect must be shown for the facility, equipment, staff and participants.</li>
                    <li>The Sibley-Ocheyedan Community School District and the City of Sibley are not responsible for any lost or stolen items.</li>
                    <li>You must enter and exit through the NORTH door at Franklin; the front door will be locked at all times.</li>
                    <li>The Elementary Gym is available for your use when school is not in session and when a scheduled recreational program is not being conducted in the gym.</li>
                </ol>
                <div class="text-center">
                    <button type="button" class="btn btn-primary">Download Membership Form</button>
                </div>
                
            </section>

            <section id="events">

            </section>

            <section id="programs">

            </section>

            <section id="gallery">

            </section>

            <section id="getInvolved">
                <h4>Get Involved</h4>
                <blockquote class="blockquote text-center">
                    <p class="mb-0">Volunteers don’t necessarily have the time; they have the heart.</p>
                    <footer class="blockquote-footer">Elizabeth Andrew</footer>
                </blockquote>
                <p>Volunteer coaches are the backbone of youth programs throughout the country. Without the help of thousands of parents signing up to 
                    coach youth sports every season, youth sports programs would cease to exist.  Volunteerism is one of the highest forms of recreation! 
                    Volunteering can have a positive impact on both your personal and professional life.  If you are passionate about sports or leisure 
                    activities, please share your love  with kids in our community.  The Sibley Recreation Department believes that winning has less to 
                    do with the scoreboard and  more to do with core values of sportsmanship, friendship, and having fun.</p>
                <div class="text-center mb-4">
                    <button type="button" class="btn btn-primary btn-lg">Coaching Volunteer Form</button>
                    <button type="button" class="btn btn-primary btn-lg">High School Community Service</button>
                </div>
                
            </section>

            <section id="contact">
                <h4>Contact</h4>
                <div class="row">
                    <div class="col-md-6">
                        <p>Sibley Parks & Recreation Director</p>
                        <ul>
                            <li>Sara Berndgen</li>
                            <li>sibley.rec@gmail.com</li>
                            <li> 712-754-2330</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <p>Recreation Board Members</p>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>Jeni Sarringar<div class="small text-muted">Term Expiration March 2019</div></div>
                            <span class="badge badge-primary badge-pill">President</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                            Dapibus ac facilisis in
                            <span class="badge badge-primary badge-pill">2</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                            Morbi leo risus
                            <span class="badge badge-primary badge-pill">1</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
        
    </div>

</div>
<?php if (Yii::$app->user->can('update_subPage')): ?>
<?= SubPageAsset::register($this); ?>
<?php endif; ?>
