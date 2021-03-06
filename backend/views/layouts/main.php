<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);
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
            <img class="d-lg-block d-md-block d-none" src="<?php //echo $bundle->baseUrl; ?>/img/logo4.png" alt="" width="395" height="105">
            <img class="d-lg-none d-md-none d-sm-block" src="<?php //echo $bundle->baseUrl; ?>/img/logo-small.png" alt="" width="254" height="53">
        </a>
        <div class="navbar-collapse collapse pt-2 pt-md-0" id="navbar1">
            <ul class="navbar-nav flex-row ml-auto">
                <li class="nav-item active">
                    <a class="nav-link px-2" href="#"><i class="fas fa-home"></i></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link px-2 dropdown-toggle" data-toggle="dropdown" href="#">City of Sibley</a>
                    <div class="dropdown-menu">
                    <a href="<?= Url::to('sibley/index') ?>" class="dropdown-item">City Government</a>
                    <a href="/sibley/location" class="dropdown-item">Location</a>
                    <a href="/sibley/lodging" class="dropdown-item">Lodging</a>
                    <a href="/sibley/food" class="dropdown-item">Eating Establishments</a>
                    <a href="/sibley/map" class="dropdown-item">Interactive Map</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link px-2 dropdown-toggle" data-toggle="dropdown" href="#">Chamber Of Commerce</a>
                    <div class="dropdown-menu">
                        <a href="/business/chamber" class="dropdown-item">Chamber of Commerce</a>
                        <a href="/business/list" class="dropdown-item">Chamber Member List'</a>
                        <a href="/business/benefits" class="dropdown-item">Chamber Member Benefits</a>  
                    </div>
                </li>
                <li class="nav-item dropdown">
                <a class="nav-link px-2 dropdown-toggle" data-toggle="dropdown" href="#">Recreation</a>
                    <div class="dropdown-menu">
                        <a href="/recreation" class="dropdown-item">Recreation Department</a>
                        <a href="/recreation/parks" class="dropdown-item">Community Parks</a>
                        <a href="/recreation/golf" class="dropdown-item">Sibley Golf and Country Club</a>
                        <a href="/recreation/camping" class="dropdown-item">Camping Facilities</a>
                        <a href="/recreation/swimming" class="dropdown-item">Swimming Facilities</a>
                        <a href="/recreation/fishing" class="dropdown-item">Fishing Opportunities</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2" href="#">Contact</a>
                </li>
            </ul>
        </div>
        <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbar2">
            <span class="navbar-toggler-icon"></span>
        </button>
        </div>
    </nav>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer bg-dark py-2">
    <div class="container text-center text-white">
        <p>&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p>
            <?php 
            if (!Yii::$app->user->isGuest) {
                echo Html::beginForm(['/site/logout'], 'post');
                echo Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                );
                echo Html::endForm();
            }
            ?>
        </p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
