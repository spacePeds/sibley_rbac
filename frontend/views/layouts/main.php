<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\assets\SlickCarouselAsset;
use common\widgets\Alert;
use common\widgets\SiteAlert;
use yii\helpers\Url;
use \yii\web\Request;
use yii\bootstrap4\Modal;

AppAsset::register($this);
SlickCarouselAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>

<div class="wrap">
    <nav class="navbar navbarA navbar-expand-md fixed-top-sm justify-content-start flex-nowrap navbar-dark bg-success">
        <div class="container">
        <a href="#" class="navbar-brand">
            <!--https://fonts.google.com/?category=Handwriting&selection.family=Great+Vibes-->
            <img class="d-lg-block d-md-block d-none" src="<?php //echo $bundle->baseUrl; ?><?= Url::to(['/']) ?>img/logo4.png" alt="" width="395" height="105">
            <img class="d-lg-none d-md-none d-sm-block" src="<?= Url::to(['/']) ?>img/logo-small.png" alt="" width="254" height="53">
        </a>
        <div class="navbar-collapse collapse pt-2 pt-md-0" id="navbar1">
            <ul class="navbar-nav flex-row ml-auto">
                <li class="nav-item active">
                    <a class="nav-link px-2" href="<?= Url::to(['/']) ?>"><i class="fas fa-home"></i></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link px-2 dropdown-toggle" data-toggle="dropdown" href="#">City of Sibley</a>
                    <div class="dropdown-menu">
                    <a href="<?= Url::to(['/sibley/city']) ?>" class="dropdown-item">City Government</a>
                    <a href="<?= Url::to(['/sibley/location']) ?>" class="dropdown-item">Location</a>
                    <a href="<?= Url::to(['/sibley/lodging']) ?>" class="dropdown-item">Lodging</a>
                    <a href="<?= Url::to(['/sibley/food']) ?>" class="dropdown-item">Eating Establishments</a>
                    <a href="<?= Url::to(['/sibley/map']) ?>" class="dropdown-item">Interactive Map</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link px-2 dropdown-toggle" data-toggle="dropdown" href="#">Chamber Of Commerce</a>
                    <div class="dropdown-menu">
                        <a href="<?= Url::to(['/sibley/chamber']) ?>" class="dropdown-item">About Sibley Chamber</a>
                        <a href="<?= Url::to(['/business/list']) ?>" class="dropdown-item">Chamber Member List</a>
                        <a href="<?= Url::to(['/sibley/chamber-benefits']) ?>" class="dropdown-item">Chamber Member Benefits</a>  
                    </div>
                </li>
                <li class="nav-item dropdown">
                <a class="nav-link px-2 dropdown-toggle" data-toggle="dropdown" href="#">Parks & Recreation</a>
                    <div class="dropdown-menu">
                        <a href="<?= Url::to(['/sibley/recreation']) ?>" class="dropdown-item">Recreation Department</a>
                        <a href="/recreation/parks" class="dropdown-item">Community Parks</a>
                        <a href="/recreation/golf" class="dropdown-item">Sibley Golf and Country Club</a>
                        <a href="/recreation/camping" class="dropdown-item">Camping Facilities</a>
                        <a href="/recreation/swimming" class="dropdown-item">Swimming Facilities</a>
                        <a href="/recreation/fishing" class="dropdown-item">Fishing Opportunities</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2" href="<?= Url::to(['/site/contact']) ?>">Contact</a>
                </li>
            </ul>
        </div>
        <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbar2">
            <span class="navbar-toggler-icon"></span>
        </button>
        </div>
    </nav>


    <nav class="navbar navbarB navbar-expand-md bg-light navbar-light p-0">
        <div class="container">
            
            <div class="navbar-collapse collapse pt-2 pt-md-0" id="navbar2">
                
                <ul class="navbar-nav ml-auto">
                    

                    <li class="nav-item d-lg-none d-md-none d-sm-block dropdown">
                        <a class="nav-link px-2 dropdown-toggle" data-toggle="dropdown" href="#">City of Sibley</a>
                        <div class="dropdown-menu">
                        <a href="<?= Url::to(['/sibley/city']) ?>" class="dropdown-item">City Government</a>
                        <a href="<?= Url::to(['/sibley/location']) ?>" class="dropdown-item">Location</a>
                        <a href="<?= Url::to(['/sibley/lodging']) ?>" class="dropdown-item">Lodging</a>
                        <a href="<?= Url::to(['/sibley/food']) ?>" class="dropdown-item">Eating Establishments</a>
                        <a href="<?= Url::to(['/sibley/map']) ?>" class="dropdown-item">Interactive Map</a>
                        </div>
                    </li>

                    <li class="nav-item d-lg-none d-md-none d-sm-block dropdown">
                        <a class="nav-link px-2 dropdown-toggle" data-toggle="dropdown" href="#">Chamber Of Commerce</a>
                        <div class="dropdown-menu">
                            <a href="<?= Url::to(['/sibley/chamber']) ?>" class="dropdown-item">About Sibley Chamber</a>
                            <a href="<?= Url::to(['/business/list']) ?>" class="dropdown-item">Chamber Member List</a>
                            <a href="<?= Url::to(['/sibley/chamber-benefits']) ?>" class="dropdown-item">Chamber Member Benefits</a>  
                        </div>
                    </li>
                    <li class="nav-item d-lg-none d-md-none d-sm-block dropdown">
                    <a class="nav-link px-2 dropdown-toggle" data-toggle="dropdown" href="#">Parks & Rec</a>
                        <div class="dropdown-menu">
                            <a href="<?= Url::to(['/sibley/recreation']) ?>" class="dropdown-item">Recreation Department</a>
                            <a href="/recreation/parks" class="dropdown-item">Community Parks</a>
                            <a href="/recreation/golf" class="dropdown-item">Sibley Golf and Country Club</a>
                            <a href="/recreation/camping" class="dropdown-item">Camping Facilities</a>
                            <a href="/recreation/swimming" class="dropdown-item">Swimming Facilities</a>
                            <a href="/recreation/fishing" class="dropdown-item">Fishing Opportunities</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown mr-3">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            I would Like to...
                        </a>
                        <div class="dropdown-menu">
                            <a href="<?= Url::to(['/sibley/calendar']) ?>" class="dropdown-item">View Local Events</a>
                            <a href="profile.html" class="dropdown-item"> Find Campground Information</a>
                            <a href="<?= Url::to(['/sibley/council']) ?>" class="dropdown-item"> View Council Meeting Agendas</a>
                            <a href="<?= Url::to(['/sibley/spiritual-centers']) ?>" class="dropdown-item"> View Community Spiritual Centers</a>
                            <a href="settings.html" class="dropdown-item"> Pay A Utility Bill</a>
                            <?php if (Yii::$app->user->can('update_alert')) : ?>
                                <a href="#" id="createSiteWideAlert" class="dropdown-item"> Add Site-Wide Alert</a>
                            <?php endif; ?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<div class="container px-0">
    <?= SiteAlert::widget() ?>
</div>

<div class="container px-0 body-content">
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <div class="container">
        <?= Alert::widget() ?>
    </div>

    <?= $content ?>
</div>
     

<footer class="footer bg-dark py-2 text-white text-center">
    <div class="container">
        <p class="text-center">
            &copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?>
            <br/><span class="text-muted small">Information herein deemed reliable but not guaranteed</span>
        </p>

        <?php 
        //$backEndBaseUrl = str_replace('/frontend/web', '/backend/web', (new Request)->getBaseUrl());

        if (Yii::$app->user->isGuest) {
            echo '<a href="'. Url::to(['/site/login']). '">Login</a>';          
        } else {
        
            echo Html::beginForm(['/site/logout'], 'post');
            echo Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            );
            echo Html::endForm();
        }   
        ?>
    </div>
</footer>
<div class="modal fade" id="genericModal" tabindex="-1" role="dialog" aria-labelledby="genericModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="genericModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="modalContent" class="modal-body"></div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
      </div>
    </div>
  </div>
</div>


<?php
    // Modal::begin([
    //     'title' => '',
    // ]);
    // echo '<div id="modalContent"></div>';
    // Modal::end();

?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
