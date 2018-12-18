<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Business */


            

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Businesses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->user->can('update_business')) : ?>
    <div class="adminFloater shadow-sm p-3 mb-5 bg-white rounded d-flex flex-column text-center">
        <div class="p-2"><?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?></div>
        <?php if (Yii::$app->user->can('delete_business')) : ?>
            <div class="p-2">
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<div class="business-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php //echo '<pre>'.print_r($model, true).'</pre>'; ?>
    <?php //echo '<pre>'.print_r($contactMethods, true).'</pre>'; ?>
    <?php //echo '<pre>'.print_r($categories).'</pre>'; ?>

    

    

    <div class="card h-100 mb-3">
        <h5 class="card-header" data-id="<?= $model->id ?>"><?= $model->name ?></h5>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><?= $model->address1 ?></li>
                        <li class="list-group-item"><?= $model->address2 ?></li>
                        <li class="list-group-item"><?= $model->city ?>, <?= $model->state ?> <?= $model->zip ?></li>
                        <li class="list-group-item"><?= $model->url ?></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <div class="display-5">Categories</div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($model->category_ids as $idx) : ?>
                            <li class="list-group-item"><?= $categories[$idx] ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="display-5">Contact Methods</div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($contactMethods as $idx => $contactMethod) : ?>
                            <li class="list-group-item"><?= $contactMethod['method'] ?>: <?= $contactMethod['contact'] ?> - <?= $contactMethod['description'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                        
                    <div class="mb-1">Chamber Member: <?= ($model->member) ? 'Yes' : 'No' ?></div>

                    <div class="mb-1"><?= $model->note ?></div>

                </div>
            </div>
        </div>
    </div>

</div>
