<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

if (count($details) < 1) {
    $title = 'Page not found.';
    $body = '<p>This page is currently unconfigured.</p>';
    $url = Url::to(['/page/create']);
    $linkText = 'Create Page';
} else {
    $title = isset($details['title']) ? $details['title'] : '';
    $body = isset($details['body']) ? $details['body'] : '';
    $url = Url::to(['/page/update']) . '/'.$key;
    $linkText = 'Edit ' . $this->title . ' Page';
}
$standardHeaderImages = [];

//import phone# formatting functions
$this->render('_helperFormatPhone', []);

$this->params['breadcrumbs'][] = $title;
?>
<?php //echo '<pre>' . print_r($details['headerImages'],true) . '</pre>' ?>

<?php if (!empty($details['fb_token'])):
?>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=<?=$details['fb_token']?>&autoLogAppEvents=1"></script>
<?php endif; ?>

<!-- define header images -->
<?php if (isset($details['headerImages'])): ?>
    <?php foreach ($details['headerImages'] as $headerImage) {
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
            $style = '';
            if (!empty($offset)) {
                $style = "margin-top:".$offset."px;";
            }
            $standardHeaderImages[] = '<img src="'.$imgPath.'" height="'.$height.'" class="'.$class.'" style="'.$style.'">';       
        }
    }
    ?>
<?php endif; ?>


<section id="genericPage" class="container">

    <div class="container bg-white clearfix">
        <?php if (Yii::$app->user->can('update_location')) : ?>
            <div class="text-right adminFloaterRev2 shadow-sm p-1 mb-2 bg-white rounded">
                <a href="<?= $url ?>" role="button" class="btn btn-primary"><?=$linkText?></a>
            </div>
        <?php endif; ?>

        <h3><?=$details['title']?></h3>

        <?php foreach ($standardHeaderImages as $leadImage) {
            echo $leadImage;
        }
        ?>
        
        <?= $body ?>
    </div>
</section>

<?php if (!empty($details['fb_token']) && !empty($details['fb_link'])): ?>
    <section class="text-center" id="facebook">
        <div class="container bg-white clearfix">
        <h4 class="text-left"><?=$details['title']?> Facebook Feed</h4>
        <div class="fb-page" data-href="https://www.facebook.com/<?=$details['fb_link']?>" data-tabs="timeline" data-width="500" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
            <blockquote cite="https://www.facebook.com/<?=$details['fb_link']?>" class="fb-xfbml-parse-ignore">
                <a href="https://www.facebook.com/<?=$details['fb_link']?>"><?=$details['title']?></a>
            </blockquote>
        </div>
        </div>
    </section>
<?php endif; ?>


<?php if (isset($details['linkedOrganizations'])): ?>
    <?= $this->render('_linkedOrg', [
        'page' => $details,               
    ]) ?>
<?php endif; ?>



    