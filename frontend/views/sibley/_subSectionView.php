<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php foreach ($subSections as $subSection): ?>
    <?php if ($subSection['type'] == 'section'): ?>
        <section id="<?=str_replace('#','',$subSection['path'])?>">
            <?php if (isset($role['superAdmin']) || (Yii::$app->user->can('update_subPage') && Yii::$app->user->can('update_page_'.$adminGroup))): ?>
                <a href="<?=Url::to('/sub-page/update')?>/<?=$subSection['id']?>" class="float-right btn btn-outline-success btn-sm"><i class="fas fa-plus-square"></i> Update Section</a>
            <?php endif; ?>
            
            <?php if (isset($role['superAdmin']) || (Yii::$app->user->can('delete_subPage') && Yii::$app->user->can('update_page_'.$adminGroup))): ?>
                <?= Html::a('<i class="far fa-trash-alt"></i> ' . Yii::t('app', 'Delete Section'), ['sub-page/delete', 'id' => $subSection['id']], [
                    'class' => 'float-right btn btn-outline-danger btn-sm',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this Section?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
            <h4><?= $subSection['title'] ?></h4>
            <?= $subSection['body'] ?>
            <?php //echo '<pre>' . print_r($subSection['documents']) . '</pre>'; ?>
            <?php foreach ($subSection['documents'] as $document): ?>
                <?php 
                $path = '/'.$document['path'] . $document['name'];
                $size = $document['size'];
                $label = $document['label'];
                $pos = strpos($document['type'], 'image');
                if ($pos !== false) {
                    //image
                    ?>

                    <div data-id="<?=$document['id']?>">
                    <img class="rounded mx-auto" width="75" src="<?=$path?>">
                    <?php if (isset($role['superAdmin']) || (Yii::$app->user->can('update_subPage') && Yii::$app->user->can('update_page_'.$adminGroup))): ?>
                        <?=$label?>
                        <a data-id="<?=$document['id']?>" class="small text-muted doDelete" href="#">Delete</a>
                    <?php endif; ?>
                    </div>

                    <?php
                }
                $pos = strpos($document['type'], 'pdf');
                if ($pos !== false) {
                    //pdf 
                    ?>
                    <div data-id="<?=$document['id']?>">
                    <a role="button" class="btn btn-outline-primary mx-auto" target="_blank" href="<?=$path?>"><i class="far fa-file-pdf"></i> <?=$label?></a>
                    <?php if (isset($role['superAdmin']) || (Yii::$app->user->can('update_subPage') && Yii::$app->user->can('update_page_'.$adminGroup))): ?>
                        <a data-id="<?=$document['id']?>" class="small text-muted doDelete" href="#">Delete</a>
                    <?php endif; ?>
                    </div>
                    <?php
                }
                ?>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
<?php endforeach; ?>