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


$this->params['breadcrumbs'][] = $title;
?>
<?php //echo '<pre>' . print_r($details['headerImages'],true) . '</pre>' ?>


<section id="genericPage" class="container">
    <div class="container bg-white clearfix">
        <?php if (Yii::$app->user->can('update_location')) : ?>
            <div class="text-right adminFloaterRev2 shadow-sm p-1 mb-2 bg-white rounded">
                <a href="<?= $url ?>" role="button" class="btn btn-primary"><?=$linkText?></a>
            </div>
        <?php endif; ?>

        <?php foreach ($details['headerImages'] as $headerImage) {
            $imgPath = $headerImage['image_path'];
            $height = !empty($headerImage['height']) ? $headerImage['height'] : '';
            $offset = !empty($headerImage['offset']) ? $headerImage['offset'] : '';
            $class = 'float-left';
            if ($headerImage['display'] == 'rounded') {
                $class = 'rounded';
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
        ?>
        <img src="<?=$imgPath?>" height="<?=$height?>" class="<?=$class?>">
        <?php
        }
        }
        ?>
        <?= $body ?>
    </div>

    <?php if (isset($details['linkedOrganizations'])): ?>
        <?php foreach ($details['linkedOrganizations'] as $organization): 
            if (!empty($organization['url'])) {
                $organization['name'] = '<a target="_blank" href="'.$organization['url'].'"><i class="fas fa-link"></i> '.$organization['name'].'</a>';
            }
        ?>

            <div class="row border-bottom">
                <div class="col-md-2">
                    <img src="/img/assets/placeholder-image.jpg" alt="" class="img-thumbnail m-3 img-fluid" height="200">
                </div>
                <div class="col-md-5">
                    <h4><?=$organization['name']?></h4>
                    <p><?=$organization['address1']?><?=!empty($organization['address2']) ? '<br/>' . $organization['address2'] : '' ?>
                        <br><?=$organization['city']?>, <?=$organization['state']?> <?=$organization['zip']?>
                    </p>
                    <?php if (!empty($organization['contact'])): ?>
                        <ul>    
                            <?php foreach ($organization['contact'] as $contact): 
                                //set font-awesome icons
                                if ($contact['method'] == 'phone') {
                                    $contact['method'] = '<i class="fas fa-mobile-alt"></i>';
                                    $contact['description'] = format_phone('us', $contact['description']);
                                }
                                if ($contact['method'] == 'email') {
                                    $contact['method'] = '<i class="far fa-envelope"></i>';
                                }
                            ?>
                                <li><?=$contact['contact']?>: <?=$contact['method']?> <?=$contact['description']?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    
                </div>
                <?php if (!empty($organization['note'])): ?>
                    <div class="col-md-5">
                        <div class="border rounded p-3">
                            <p><?= $organization['note'] ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<?php
function format_phone($country, $phone) {
    $function = 'format_phone_' . $country;
    if(function_exists($function)) {
        return $function($phone);
    }
    return $phone;
}

function format_phone_us($phone) {
    // note: making sure we have something
    if(!isset($phone{3})) { return ''; }
        // note: strip out everything but numbers 
        $phone = preg_replace("/[^0-9]/", "", $phone);
        $length = strlen($phone);
        switch($length) {
        case 7:
            return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
        break;
        case 10:
            return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
        break;
        case 11:
        return preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1($2) $3-$4", $phone);
        break;
        default:
            return $phone;
        break;
    }
}