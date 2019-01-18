<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Sibley Recreation Department';
$this->params['breadcrumbs'][] = $this->title;
?>
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
        <div class="col-md-3 bg-recSidebar">


            <div class="list-group my-3">
                <a href="#membership" class="list-group-item list-group-item-action">Membership</a>
                <a href="sibley/calendar" class="list-group-item list-group-item-action">Calendar of Events</a>
                <a href="#" class="list-group-item list-group-item-action">Current Programs</a>
                <a href="#" class="list-group-item list-group-item-action">Image Gallery</a>
                <a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">Get Involved</a>
                <a href="#contact" class="list-group-item list-group-item-action">Contact</a>
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
            
            <?= $details['body'] ?>

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

