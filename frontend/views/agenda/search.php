<?php
use yii\widgets\LinkPager;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;


$this->title = 'Council Meetings Search';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sibley City Council Meetings'), 'url' => ['sibley/council']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if ($pages->totalCount == 0): ?>
    <div class="jumbotron m-5">
        <p class="lead">No results found fitting your search criteria</p>
        <h5>Refine Search</h5>
        <?php $form = ActiveForm::begin([ 
            'id' => 'meetingSearchForm', 
            'action' => ['agenda/search'], 
            'method' => 'get'
        ]); ?>
        <div class="input-group mb-1">
            <input type="text" class="form-control searchTerm" placeholder="Search" name="searchTerm" aria-label="Search" aria-describedby="button-search">
            <div class="input-group-append">
                <button class="btn btn-outline-success" type="submit" id="button-search">Search</button>
            </div>
        </div>
        <small id="searchHelpBlock" class="form-text text-muted"></small>
        <?php ActiveForm::end(); ?>
        <a role="button" class="btn btn-outline-secondary mt-4" href="<?=Url::to('/sibley/council')?>">Return to Council Meeting List</a>
    </div>

<?php else: ?>

<div class="container">
    <p>Found <strong><?=$pages->totalCount?></strong> Results:</p>

    <?php foreach ($models as $model): ?>
            <div class="row mb-2">
                <div class="col">
                    <h6><?=Html::a(ucfirst($model['type']).' Meeting: ' . date("F j, Y", strtotime($model['date'])),'/sibley/council/'.$model['aId'])?></h6>
                    <div>
                        <?=$model['aBody']?>
                    </div>
                    <?php if (!empty($model['mId'])): ?>
                        <div class="small text-muted">
                            Minutes Available
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            // display $model here
            //echo '<pre>' . print_r($model,true) . '</pre>';
            ?>
            
    
    <?php endforeach; ?>
</div>

<?php endif;?>


<?php
//echo '<pre>' . print_r($pages, true) . '</pre>';
?>

<div class="text-center">
<?php
// display pagination
//https://stackoverflow.com/questions/39317665/yii2-pagination-change-style
echo LinkPager::widget([
    'pagination' => $pages,
    'options' => ['class' => 'pagination justify-content-center'],
    //First option value
    'prevPageLabel' => 'Previous',
    //Last option value
    'nextPageLabel' => 'Next',
    //Current Active option value
    'activePageCssClass' => 'page-item active',
    'linkOptions' => ['class' => 'page-link'],
    'disabledListItemSubTagOptions' => [
        'tag' => 'a',
        'class' => 'page-link'
    ],
]);
?>
</div>