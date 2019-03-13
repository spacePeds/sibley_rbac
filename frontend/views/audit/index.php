<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Audits');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Audit'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'record_id',
            'field',
            'old_value:ntext',
            'new_value:ntext',
            //'date',
            //'update_user',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
