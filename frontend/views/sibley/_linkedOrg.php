<?php
use yii\helpers\Html;
?>

<?php if (isset($page['linkedOrganizations'])): ?>
    <?php foreach ($page['linkedOrganizations'] as $organization): 
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