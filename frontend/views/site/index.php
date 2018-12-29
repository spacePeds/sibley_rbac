<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
use davidjeddy\RssFeed\RssReader;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <?php if (Yii::$app->user->can('update_alert')) : ?>
        <div class="adminFloater shadow-sm p-3 mb-5 bg-white rounded">
            <a href="<?= Url::to(['/alert/index']) ?>" role="button" class="btn btn-primary">Edit City Alert</a>
        </div>
    <?php endif; ?>

    <?php if (count($alerts) > 0): ?>
        <?php //echo '<pre>' . print_r($alerts,true) . '</pre>' ?>
        <?php foreach ($alerts as $idx=>$alert): ?>
            <div class="alert alert-<?= $alert['type']?>" role="alert"><?= $alert['message']?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <header>
      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner" role="listbox">
          <!-- Slide One - Set the background image for this slide in the line below -->
          <div class="carousel-item active" style="background-image: url('http://placehold.it/1900x1080')">
            <div class="carousel-caption d-none d-md-block">
              <h3>First Slide</h3>
              <p>This is a description for the first slide.</p>
            </div>
          </div>
          <!-- Slide Two - Set the background image for this slide in the line below -->
          <div class="carousel-item" style="background-image: url('http://placehold.it/1900x1080')">
            <div class="carousel-caption d-none d-md-block">
              <h3>Second Slide</h3>
              <p>This is a description for the second slide.</p>
            </div>
          </div>
          <!-- Slide Three - Set the background image for this slide in the line below -->
          <div class="carousel-item" style="background-image: url('http://placehold.it/1900x1080')">
            <div class="carousel-caption d-none d-md-block">
              <h3>Third Slide</h3>
              <p>This is a description for the third slide.</p>
            </div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </header>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-3">
                <h3>Sibley Daily News</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <a href="http://www.osceolacountydailynews.com/" target="_blank">KIWA Sibley News</a>
                    </li>
                </ul>
                
                <?php 
                /*
                //https://www.yiiframework.com/extension/davidjeddy/yii2-rss-reader
                echo RssReader::widget([
                    'channel'   => 'https://www.feedspot.com/infiniterss.php?q=site:http%3A%2F%2Fwww.cinemablend.com%2Frss-all.xml',
                    'itemView'  => 'item',
                    'pageSize'  => 5,
                    'wrapClass' => 'rss-wrap',
                    'wrapTag'   => 'div',
                ]);
                */
                ?>
                
            </div>
            <div class="col-lg-6">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
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
                <?php endif; ?>    
            
            </div>
        </div>

    </div>
</div>
