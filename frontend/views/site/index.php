<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
use davidjeddy\RssFeed\RssReader;

/*
<?php if (Yii::$app->user->can('update_alert')) : ?>
        <div class="adminFloater shadow-sm p-3 mb-5 bg-white rounded">
            <a href="<?= Url::to(['/alert/index']) ?>" role="button" class="btn btn-primary">Edit City Alert</a>
        </div>
    <?php endif; ?>
*/

$this->title = 'Sibley: Highlight of Iowa!';
?>
<div class="site-index">

    

    <?php if (count($alerts) > 0): ?>
        <?php //echo '<pre>' . print_r($alerts,true) . '</pre>' ?>
        <?php foreach ($alerts as $idx=>$alert): ?>
            <div class="alert alert-<?= $alert['type']?> mb-0 p-2" role="alert"><?= $alert['message']?>
                <a href="/alert/update/<?= $alert['id']?>" title="Update" aria-label="Update" class="float-right"><i class="fas fa-edit"></i></a>
            </div>
            <!--<div class="alert alert-danger mb-0 p-2" role="alert"><span class="border border-secondary rounded p-1 m-1"><strong>City Notice:</strong></span>i am an alert</div>-->
        <?php endforeach; ?>
    <?php endif; ?>

    <section id="showcase">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1" class=""></li>
            <li data-target="#myCarousel" data-slide-to="2" class=""></li>
            <li data-target="#myCarousel" data-slide-to="3" class=""></li>
            <li data-target="#myCarousel" data-slide-to="4" class=""></li>
            </ol>

            <div class="carousel-inner">
            <div class="carousel-item carousel-image-1 active">
            <div class="container">
            <div class="carousel-caption d-none d-sm-block text-right pr-4">
            <h1 class="display-4">Osceola County Courthouse</h1>
            <p class="lead">Built in 1902, this landmark dominates Sibley's center.</p>
            <a target="blank" href="http://www.osceolacountyia.org/" class="btn btn-success">Visit Courthouse</a>
            </div>
            </div>
            </div>

            <div class="carousel-item carousel-image-2">
            <div class="container">
            <div class="carousel-caption d-none d-sm-block pl-4">
            <h1 class="display-4">Downtown Sibley</h1>
            <p class="lead">Several unique storefronts dot Sibley's downtown.</p>
            </div>
            </div>
            </div>

            <div class="carousel-item carousel-image-3">
            <div class="container">
            <div class="carousel-caption d-none d-sm-block text-right mb-5">
            <h1 class="display-4">Sibley Golf and Country Club</h1>
            <p class="lead">Spend several hours relaxing at our beautiful golf course.</p>
            <a href="https://www.facebook.com/SibleyGolfCountryClub/" class="btn btn-success btn-lg">Learn More</a>
            </div>
            </div>
            </div>

            <!--Sibley's real pride is in its people. The dedicated, professional, caring individuals who create the growth and spirit of opportunity make the community a great place to live-->

            </div>
<!--
            <a href="#myCarousel" class="carousel-control-prev" data-slide="prev" >
            <span class="carousel-control-prev-icon"></span>
            </a>
            <a href="#myCarousel" class="carousel-control-next" data-slide="next" >
            <span class="carousel-control-next-icon"></span>
            </a>
-->
        </div>
    </section>

    <section>

        <div class="row">
            <div class="col-lg-3">
                <h3>Sibley Daily News</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <a href="http://www.osceolacountydailynews.com/" target="_blank">KIWA Sibley News</a>
                    </li>
                </ul>
                
                <h3>KIWA Regional News</h3>
                <ul class="list-group list-group-flush">
                <?php foreach($feed['entries'] as $record): ?>
                    <li class="list-group-item small">
                        <a href="<?=$record['link']?>" target="_blank"><?=$record['title']?></a>
                    </li>
                <?php endforeach; ?>
                </ul>
                <?php //echo '<pre>' . print_r($feed,true) . '</pre>'; ?>
                
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <?php echo '<pre>' . print_r($events, true) . '</pre>'; ?>
                    </div>
                </div>
            
            </div>
            <div class="col-lg-3">
                <?php if (Yii::$app->user->can('create_link')) : ?>
                    <a class="btn btn-outline-success btn-sm" href="<?= Url::to(['/link/create']) ?>" title="Create" aria-label="Create"><i class="fas fa-plus-square"></i></a>                   
                <?php endif;?>
                <?php //echo '<pre>' . print_r($localInterest,true) . '</pre>'; ?>
                <?php if (!empty($localInterest)): ?>
                    <h3><?=$localInterest['group']?></h3>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($localInterest['links'] as $link): ?>
                        <li class="list-group-item">
                            <?php if (Yii::$app->user->can('update_link')) : ?>
                                <div class="cardEdit">
                                    <a class="btn btn-outline-primary btn-sm" href="<?= Url::to(['/link/update/' . $link['id']]) ?>" title="Update" aria-label="Update"><i class="fas fa-edit"></i></a>

                                    <?php if (Yii::$app->user->can('delete_link')): ?>
                                        <?= Html::a('<i class="fas fa-trash"></i>', ['/link/delete', 'id' => $link['id']], [
                                            'class' => 'btn btn-outline-danger btn-sm',
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
                <?php else: ?>
                    <p>There are currently no relevant links of interest</p>
                <?php endif; ?>    
            
            </div>
        </div>

                        </section>
</div>
