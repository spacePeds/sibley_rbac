<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Alert */

$this->title = Yii::t('app', 'Create Notification');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Alerts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alert-create">

    

    <?php //echo '<pre>' . print_r($group,true) . '</pre>' ?>

    <?= $this->render('_form', [
        'model' => $model,
        'group' => $group,
    ]) ?>

</div>
