<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Alert */

$this->title = Yii::t('app', 'Create Alert');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Alerts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alert-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php //echo '<pre>' . print_r($group,true) . '</pre>' ?>

    <?= $this->render('_form', [
        'model' => $model,
        'group' => $group,
    ]) ?>

</div>
