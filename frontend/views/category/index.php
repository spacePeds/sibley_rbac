<?php

use yii\helpers\Html;
use frontend\assets\BusinessAsset;
use yii\bootstrap4\Modal;
use yii\helpers\Url;

BusinessAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>


<?php //echo '<pre>' . print_r($model, true) . '</pre>' ?>

<div class="category-index container">
    <?php if (Yii::$app->user->can('create_category')) : ?>
        <div class="text-right adminFloaterRev2 shadow-sm p-1 mb-2 bg-white rounded">
            <?= Html::a(Yii::t('app', 'View Organization List'), [Url::to('/business/list')], ['class' => 'btn btn-link']) ?>
            <?= Html::button('Create Category', [
                'value' => Url::to('@web/category/create'), 
                'class' => 'btn btn-success',
                'id' => 'btnModalCategory']) ?>
        </div>
    <?php endif; ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Category</th>
        <th>Description</th>
        <th>Creation Date</th>
        <th>In Use</th>
        <th>&nbsp;</th>
    </th>
    <tbody>
        <?php foreach($model as $category): ?>
            <tr>
                <td><?= $category['category'] ?></td>
                <td><?= $category['description'] ?></td>
                <td><?= ($category['created_dt']) ? date('m/d/Y h:ia', strtotime($category['created_dt'])) : 'N/A' ?></td>
                <td><?= $category['catCount'] ?></td>
                <td>
                    <?php if (Yii::$app->user->can('update_category')) : ?>
                        <?= Html::button('<i class="fas fa-edit"></i>', [
                            'value' => Url::to('@web/category/update/'.$category['id']), 
                            'class' => 'btn btn-primary btn-sm btnModalCategoryEdit'
                        ]) ?>
                    <?php endif; ?>    
                    <?php if ($category['catCount'] < 1 && Yii::$app->user->can('delete_category')): ?>
                        <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $category['id']], [
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]) ?>   
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    </table>  
</div>
<?php
    Modal::begin([
        
        'id' => 'genericModal'
    ]);
    echo '<div id="modalContent"></div>';
    Modal::end();

?>