<?php
use yii\helpers\Url;

if (count($page) < 1) {
    $body = '<p>This page is currently unconfigured.</p>';
    $url = Url::to(['/page/create']);
    $linkText = 'Create Page';
} else {
    $body = isset($page['body']) ? $page['body'] : '';
    $url = Url::to(['/page/update']) . '/'. $page['id'];
    $linkText = 'Edit ' . $page['title'] . ' Page';
}
?>

<!--header images-->
<?php
if (isset($page['headerImages']['parallax'])) {
    echo $this->render('_paralax', ['images'=>$page['headerImages']['parallax']]);
}
?>
<div class="container bg-white clearfix">
    <?php if (Yii::$app->user->can('update_location')) : ?>
        <div class="text-right adminFloaterRev2 shadow-sm p-1 mb-2 bg-white rounded">
            <a href="<?= $url ?>" role="button" class="btn btn-primary"><?=$linkText?></a>
        </div>
    <?php endif; ?>

    <h3><?=$page['title']?></h3>

    <?php 
    if (isset($page['headerImages']['normal']) || isset($page['headerImages']['rounded'])) {
        echo $this->render('_standardImage', ['headerImages'=>$page['headerImages']]);
    }
    ?>
    
    <?= $body ?>

    <!--facebook-->
    <?php if (!empty($page['fb_token']) && !empty($page['fb_link'])): ?>
        <section class="text-center" id="facebook">
        <h4 class="text-left"><?=$page['title']?> Facebook Feed</h4>
        <div class="fb-page" data-href="https://www.facebook.com/<?=$page['fb_link']?>" data-tabs="timeline" data-width="500" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
            <blockquote cite="https://www.facebook.com/<?=$page['fb_link']?>" class="fb-xfbml-parse-ignore">
                <a href="https://www.facebook.com/<?=$page['fb_link']?>"><?=$page['title']?></a>
            </blockquote>
        </div>
        </section>
    <?php endif; ?>

    <?php if (isset($page['linkedOrganizations'])): ?>
        <?= $this->render('_linkedOrg', [
            'page' => $page,               
        ]) ?>
    <?php endif; ?>

</div>