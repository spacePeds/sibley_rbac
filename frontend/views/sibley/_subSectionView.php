<?php
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Get Youtube video ID from URL
 *
 * @param string $url
 * @return mixed Youtube video ID or FALSE if not found
 */
function getYoutubeIdFromUrl($url) {
    $parts = parse_url($url);
    if(isset($parts['query'])){
        parse_str($parts['query'], $qs);
        if(isset($qs['v'])){
            return $qs['v'];
        }else if(isset($qs['vi'])){
            return $qs['vi'];
        }
    }
    if(isset($parts['path'])){
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path)-1];
    }
    return false;
}
?>

<?php foreach ($subSections as $subSection): ?>
    
    <section id="<?=str_replace('#','',$subSection['path'])?>">
        <?php if (isset($role['superAdmin']) || (Yii::$app->user->can('update_subPage') && Yii::$app->user->can('update_page'.$adminGroup)) && $subSection['type'] != 'fb'): ?>
            <a href="<?=Url::to('/sub-page/update')?>/<?=$subSection['id']?>" class="float-right btn btn-outline-success btn-sm"><i class="fas fa-plus-square"></i> Update Section</a>
        <?php endif; ?>
        
        <?php if (isset($role['superAdmin']) || (Yii::$app->user->can('delete_subPage') && Yii::$app->user->can('update_page'.$adminGroup)) && $subSection['type'] != 'fb'): ?>
            <?= Html::a('<i class="far fa-trash-alt"></i> ' . Yii::t('app', 'Delete Section'), ['sub-page/delete', 'id' => $subSection['id']], [
                'class' => 'float-right btn btn-outline-danger btn-sm',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this Section?'),
                    'method' => 'post',
                ],
            ]) ?>
            <?php if ($subSection['type'] == 'xlink'): ?>
                <?php if(Yii::$app->user->can('update_page'.$adminGroup)): ?>
                    <h4><?= $subSection['title'] ?></h4>
                <?php endif; ?>
            <?php endif; ?>
            
        <?php endif; ?>
        
        <?php if ($subSection['type'] == 'xlink'): ?>
            <?php if ($subSection['path'] != '' && strpos($subSection['path'], 'https://youtu') !== false): ?>
                <?php
                //extract unique identifier and embed on page
                $videoId = getYoutubeIdFromUrl($subSection['path']);
                if ($videoId !== false) {
                    ?>
                    <h4><?= $subSection['title'] ?></h4>
                    <div class="embed-responsive embed-responsive-16by9">
                    <iframe width="560" height="315" class="embed-responsive-item" src="https://www.youtube.com/embed/<?=$videoId?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <?php
                }
                ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($subSection['type'] == 'section'): ?>
            <h4><?= $subSection['title'] ?></h4>
            <?= $subSection['body'] ?>

            <?php if (!empty($subSection['documents'])): ?>
                
                        <?php //echo '<pre>' . print_r($subSection['documents'], true) . '</pre>'; ?>
                        <div id="sectionCarousel<?=$subSection['id']?>" class="carousel slide carousel4Up border border-dark rounded p-2" data-ride="carousel" data-interval="false">

                        <?php if (count($subSection['documents']) > 4): ?>
                            <ol class="carousel-indicators">
                                <li data-target="#sectionCarousel<?=$subSection['id']?>" data-slide-to="0" class="active"></li>
                                <li data-target="#sectionCarousel<?=$subSection['id']?>" data-slide-to="1"></li>
                            </ol>
                        <?php endif; ?>

                            <!-- Carousel items -->
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="row">
                                        <?php 
                                        $first = true;
                                        $count = 0;
                                        foreach ($subSection['documents'] as $idx=> $document) {
                                            $path = '/'.$document['path'] . $document['name'];
                                            $size = $document['size'];
                                            $label = $document['label'];
                                            $pos = strpos($document['type'], 'image');
                                            if ($idx%4 == 0 && !$first) {
                                                echo '</div><!--.row-->';
                                                echo '</div><!--carousel-item-->';
                                                echo '<div class="carousel-item">';
                                                echo '<div class="row">';
                                            }
                                            $first = false;
                                            ?>
                                            <div class="col-md-3">
                                                <div class="card h-100">
                                                      
                                                    <?php if ($pos !== false): ?>                                                           
                                                        <a href="<?=$path?>" data-toggle="lightbox" data-gallery="example-gallery"><img data-id="<?=$idx?>" src="<?=$path?>" alt="<?=$label?>" class="card-img-top"></a>

                                                        <div class="card-body text-center p-1">
                                                            <div class="d-none d-md-block small text-dark">
                                                                <div><?=$label?></div>
                                                                <?php if (isset($role['superAdmin']) || (Yii::$app->user->can('update_subPage') && Yii::$app->user->can('update_page'.$adminGroup))): ?>
                                                                    <div><a data-id="<?=$document['id']?>" data-confirm="Are you sure you wish to delete this image?" class="text-muted doDelete" href="#">Delete</a></div>
                                                                <?php endif; ?>

                                                            </div>
                                                        </div>
                                                    <?php else: ?>

                                                    
                    
                                                        <div data-id="<?=$document['id']?>">
                                                            <a role="button" class="btn btn-outline-primary mx-auto" target="_blank" href="<?=$path?>"><img data-id="<?=$idx?>" src="/img/pdf-placeholder.png" alt="<?=$label?>" style="max-width:100%;"></a>
                                                            
                                                            <div class="card-body text-center p-1">
                                                                <div class="d-none d-md-block small text-dark">
                                                                    <div><?=$label?></div>
                                                                    <?php if (isset($role['superAdmin']) || (Yii::$app->user->can('update_subPage') && Yii::$app->user->can('update_page'.$adminGroup))): ?>
                                                                        <div><a data-id="<?=$document['id']?>" data-confirm="Are you sure you wish to delete this image?" class="text-muted doDelete" href="#">Delete</a></div>
                                                                    <?php endif; ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif;?>
                                                               
                                                    
                                                </div>
                                                
                                            </div>
                                        <?php    
                                            $count ++;
                                        } 
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <!--.carousel-inner-->
                            <?php if ($count > 4): ?>
                                <a class="carousel-control-prev" href="#sectionCarousel<?=$subSection['id']?>" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                                </a>

                                <a class="carousel-control-next" href="#sectionCarousel<?=$subSection['id']?>" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                                </a>
                            <?php endif; ?>
                        </div>
                        <!--.Carousel-->

            <?php endif; ?>


        <?php endif; ?>   
        
        <?php if ($subSection['type'] == 'fb' && !empty($facebook['fb_link'])): ?>
            <h4><?= $subSection['title'] ?></h4>
            <div class="fb-page" data-href="https://www.facebook.com/<?=$facebook['fb_link']?>" data-tabs="timeline" data-width="500" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                <blockquote cite="https://www.facebook.com/<?=$facebook['fb_link']?>" class="fb-xfbml-parse-ignore">
                    <a href="https://www.facebook.com/<?=$facebook['fb_link']?>"><?=$facebook['title']?></a>
                </blockquote>
            </div>

        <?php endif; ?>

    </section>
    
    

<?php endforeach; ?>

<?php
/*
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
                    <a href="<?=$path?>" data-toggle="lightbox" data-gallery="example-gallery"><img class="rounded mx-auto" width="75" src="<?=$path?>"></a>
                    <?php if (isset($role['superAdmin']) || (Yii::$app->user->can('update_subPage') && Yii::$app->user->can('update_page'.$adminGroup))): ?>
                        <?=$label?>
                        <a data-id="<?=$document['id']?>" data-confirm="Are you sure you wish to delete this image?" class="small text-muted doDelete" href="#">Delete</a>
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
                    <?php if (isset($role['superAdmin']) || (Yii::$app->user->can('update_subPage') && Yii::$app->user->can('update_page'.$adminGroup))): ?>
                        <a data-id="<?=$document['id']?>" class="small text-muted doDelete" href="#">Delete</a>
                    <?php endif; ?>
                    </div>
                    <?php
                }
                ?>
            <?php endforeach; ?>
*/
?>