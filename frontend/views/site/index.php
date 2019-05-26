<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
use davidjeddy\RssFeed\RssReader;

$js = <<<EOF
$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('#showcaseCarousel').carousel({
        interval:6000
    });
})
EOF;

$this->title = 'Sibley: Highlight of Iowa!';
?>
<div class="site-index">

    <div id="showcaseCarousel" class="carousel slide" data-ride="carousel" > <!--data-interval="false"-->
        <ol class="carousel-indicators">
            <li data-target="#showcaseCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#showcaseCarousel" data-slide-to="1" class=""></li>
            <li data-target="#showcaseCarousel" data-slide-to="2" class=""></li>
            <li data-target="#showcaseCarousel" data-slide-to="3" class=""></li>
            <li data-target="#showcaseCarousel" data-slide-to="4" class=""></li>
            <li data-target="#showcaseCarousel" data-slide-to="5" class=""></li>
        </ol> 
        <div class="carousel-inner">
            <div class="carousel-item active carousel-image-1">
                
                <img class="d-block w-100" src="<?= Url::to(['img/carousel/resized/liveShopPlay.jpg']) ?>" alt="Live, Shop, Play!">
            </div>
            <div class="carousel-item carousel-image-2">
                <div class="container">
                    <div class="carousel-caption d-none d-md-block text-left">
                        <div class="ml-4">
                            <h1 class="display-4">Downtown Sibley</h1>
                            <p class="lead">Several unique storefronts dot Sibley's downtown.</p>
                        </div>
                    </div>
                </div>
                <img class="d-block w-100" src="<?= Url::to(['img/carousel/resized/downtown.jpg']) ?>" alt="Second slide">
            </div>
            <div class="carousel-item carousel-image-3">
                <div class="container">
                    <div class="carousel-caption d-none d-md-block text-left">
                        <div class="ml-4">
                            <h1 class="display-4">Sibley Golf and Country Club</h1>
                            <p class="lead">Spend several hours relaxing at our beautiful golf course.</p>
                            <a href="https://www.facebook.com/SibleyGolfCountryClub/" class="btn btn-success btn-lg">Learn More</a>
                        </div>
                    </div>
                </div>
                <img class="d-block w-100" src="<?= Url::to(['img/carousel/resized/golfCourse.jpg']) ?>" alt="Third slide">
            </div>
            <div class="carousel-item carousel-image-4">
                <div class="container">
                    <div class="carousel-caption d-none d-md-block text-right">
                        <div class="mr-4">
                            <h1 class="display-4">Sibley-Ocheyedan Campus</h1>
                            <p class="lead">Sibley takes great pride in its schools.</p>
                            <a href="http://thegenerals2.socsdit.org/" class="btn btn-success btn-lg">Learn More</a>
                        </div>
                    </div>
                </div>
                <img class="d-block w-100" src="<?= Url::to(['img/carousel/resized/schools.jpg']) ?>" alt="Fourth slide">
            </div>
            <div class="carousel-item carousel-image-5">
                <div class="container">
                    <div class="carousel-caption d-none d-md-block text-right">
                        <div class="mr-4">
                            <h1 class="display-4">Why not stay a while?</h1>
                            <p class="lead">Learn more about housing and employment options.</p>
                            <a href="https://www.osceolacountyia.com/" class="btn btn-success btn-lg">Learn More</a>
                        </div>
                    </div>
                </div>
                <img class="d-block w-100" src="<?= Url::to(['img/carousel/resized/golf.jpg']) ?>" alt="Fifth slide">
            </div>
            <div class="carousel-item carousel-image-6">
                <div class="container">
                    <div class="carousel-caption d-none d-md-block text-right" style="text-shadow: 2px 2px 4px #6c6c6c;">
                    <div class="mr-4">
                    <h1 class="display-4">Osceola County Courthouse</h1>
                    <p class="lead">Built in 1902, this landmark dominates Sibley's center.</p>
                    <a target="blank" href="http://www.osceolacountyia.org/" class="btn btn-success">Visit Courthouse</a>
                    </div>
                    </div>
                </div>
                <img class="d-block w-100" src="<?= Url::to(['img/carousel/resized/courthouse.jpg']) ?>" alt="Osceola County Courthouse">
            </div>
        </div>
    </div>

    

    <section class="container">

        <div class="row">

            <div class="col-12 col-md-6 order-md-2">
                
                <p class="lead p-2">Sibley's real pride is in its people. The dedicated, professional, caring individuals who create the growth and spirit of opportunity make the community a great place to live</p>
                
                <div class="clearfix">
                    <a href="<?= Url::to(['/sibley/calendar/']) ?>" class="btn btn-outline-primary btn-sm float-right" data-toggle="tooltip" data-placement="top" title="View Calendar"><i class="far fa-calendar-alt"></i></a>
                    <h3>Upcoming Events</h3>
                </div>
                
                <ul class="list-group list-group-flush">
                    <?php if (count($events) < 1): ?>
                        <li class="list-group-item">There are currently no upcoming events on the calendar.</li>
                    <?php endif; ?>
                    <?php foreach ($events as $eventDate => $dayGroup): ?>

                        <?php if(strtotime($eventDate) < time()) { continue; } ?>

                        <li class="list-group-item">
                            <h4><?=date('l F jS', strtotime($eventDate))?></h4>
                            <div class="list-group">
                                <?php foreach ($dayGroup as $event): ?>
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">
                                                <?=$event['subject']?>
                                            </h5>
                                            <span style="color:<?=$event['color']?>" data-toggle="tooltip" data-placement="top" title="<?=$event['groupDesc']?>"><?=$event['icon']?></span>
                                        </div>
                                        <?=$event['description']?>
                                        <?php if (!empty($event['attachment'])): ?>
                                            <small><a target="_blank" href="<?=$event['attachment']?>" class=""><i class="far fa-file-pdf"></i> View Document</a></small>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php //echo '<pre>' . print_r($events, true) . '</pre>'; ?>
                   
            
            </div>

            <div class="col-12 col-md-3 order-md-1">
                <div class="mt-2">
                    <h3 class="pl-2">Sibley Daily News</h3>
                    <ul class="list-group list-group-flush pt-1">
                        <li class="list-group-item">
                            <a href="http://www.osceolacountydailynews.com/" target="_blank">KIWA Sibley News</a>
                        </li>
                    </ul>
                </div>

                <div class="mt-2">
                    <h3 class="pl-2">KIWA Regional News</h3>
                    <ul class="list-group list-group-flush">
                    <?php foreach($feed['entries'] as $record): ?>
                        <li class="list-group-item small">
                            <a href="<?=$record['link']?>" target="_blank"><?=$record['title']?></a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
                <?php //echo '<pre>' . print_r($feed,true) . '</pre>'; ?>
                
            </div>

            <div class="col-12 col-md-3 order-md-3" style="background-color:#385c65;">
                
                <?php //echo '<pre>' . print_r($localInterest,true) . '</pre>'; ?>
                <?php foreach($localInterest as $group => $links): ?>
                <div class="mt-2">
                    <h3 class="pl-1 text-white"><?=$group?></h3>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($links as $link): ?>
                        <li class="list-group-item">
                            <?php if (Yii::$app->user->can('update_link')) : ?>
                                <div class="cardEdit">
                                    <a class="btn btn-outline-primary btn-sm" href="<?= Url::to(['/link/update/' . $link['id']]) ?>" data-toggle="tooltip" data-placement="top" title="Update Link" aria-label="Update"><i class="fas fa-edit"></i></a>

                                    <?php if (Yii::$app->user->can('delete_link')): ?>
                                        <?= Html::a('<i class="fas fa-trash"></i>', ['/link/delete', 'id' => $link['id']], [
                                            'class' => 'btn btn-outline-danger btn-sm',
                                            'data-toggle'=>"tooltip",
                                            'data-placement'=>"top",
                                            'title'=>"Delete Link",
                                            'data' => [
                                                'confirm' => 'Are you sure you want to delete this link?',
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif;?>
                            <?php if (!empty($link['att'])) {
                                //path,size,name
                                $path = $link['att']['path'] . $link['att']['name'];
                            } else {
                                $path = $link['name'];
                            }
                            ?>
                            <a target="_blank" href="<?=$path?>"><?=$link['label']?><?= empty($link['desc']) ? '' : '<p class="mb-1 small">'.$link['desc'].'</p>' ?></a>
                        </li>
                        <?php endforeach; ?>
                        
                    </ul>
                        </div>
                <?php endforeach; ?> 
                <?php if (Yii::$app->user->can('create_link')) : ?>
                    <div class="card">
                        <a class="btn btn-outline-success btn-sm" href="<?= Url::to(['/link/create']) ?>" data-toggle="tooltip" data-placement="top" title="Create New Link" aria-label="Create"><i class="fas fa-plus-square"></i> Create Qucik-Link</a>
                    </div>                  
                <?php endif;?>
                
                <div class="mt-2">
                    <h3 class="pl-1 text-white">Upcoming City Council Meetings</h3>
                    <ul class="list-group list-group-flush">
                        <?php if (count($meetings) < 1): ?>
                            <li class="list-group-item small text-muted">There are currently no meetings scheduled.</li>
                        <?php endif; ?>
                        <?php foreach ($meetings as $meeting): ?>
                            <li class="list-group-item small text-muted">
                                <a href="<?= Url::to(['/sibley/council']) ?>/<?=$meeting['id']?>">Agenda: <?=$meeting['fmtdDt']?></a>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                <?php //echo '<pre>' . print_r($meetings, true) . '</pre>'; ?>
            </div>
        </div>

                        </section>
</div>
<?php
$this->registerJs($js);
