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
<?php //echo '<pre>' . print_r($details,true) . '</pre>' ?>


<section id="genericPage" class="container">
    <div class="container bg-white clearfix">
        <?php if (Yii::$app->user->can('update_location')) : ?>
            <div class="float-right adminFloaterRev2 shadow-sm p-1 mb-2 bg-white rounded">
                <a href="<?= $url ?>" role="button" class="btn btn-primary"><?=$linkText?></a>
            </div>
        <?php endif; ?>
        <?= $body ?>
    </div>

    <?php foreach ($details['linkedOrganizations'] as $organization): ?>

        <div class="row border-bottom">
            <div class="col-md-2">
                <img src="/img/assets/placeholder-image.jpg" alt="" class="img-thumbnail m-3 img-fluid" height="200">
            </div>
            <div class="col-md-5">
                <h4><?=$organization['name']?></h4>
                <h5><?=$organization['address1']?><?=!empty($organization['address2']) ? '<br/>' . $organization['address2'] : '' ?></h5>
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