<?php
use yii\helpers\Html;
use frontend\components\FrontendController;
?>

<?php if (isset($page['linkedOrganizations'])): ?>
    <?php foreach ($page['linkedOrganizations'] as $organization): 
        if (!empty($organization['url'])) {
            $organization['name'] = '<a target="_blank" href="'.$organization['url'].'">'.$organization['name'].'</a>';
        }
    ?>

        <div class="row row-bordered">
            <div class="col-md-2">
                <img src="/img/assets/placeholder-image.jpg" alt="" class="img-thumbnail m-2 img-fluid" height="200">
            </div>
            <div class="col-md-5">
                <h4 class="mt-2"><?=$organization['name']?></h4>
                <p><?=$organization['address1']?><?=!empty($organization['address2']) ? '<br/>' . $organization['address2'] : '' ?>
                    <br><?=$organization['city']?>, <?=$organization['state']?> <?=$organization['zip']?>
                </p>
                <?php if (!empty($organization['contact'])): ?>
                    <ul>    
                        <?php foreach ($organization['contact'] as $contact): 
                            //set font-awesome icons
                            if ($contact['method'] == 'phone') {
                                $contact['method'] = '<i class="fas fa-mobile-alt"></i>';
                                
                            }
                            if ($contact['method'] == 'email') {
                                $contact['method'] = '<i class="far fa-envelope"></i>';
                            }
                            if (!empty($contact['description'])) {
                                $contact['description'] = ': ' . $contact['description'];
                            }
                        ?>
                            <li><?=$contact['method']?> <?=FrontendController::format_phone('us', $contact['contact'])?> <?=$contact['description']?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                
            </div>
            <?php if (!empty($organization['note'])): ?>
                <div class="col-md-5">
                    <div class="border rounded p-2 m-2">
                        <div class="small text-muted">Additional Details:</div>
                        <p><?= $organization['note'] ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php
