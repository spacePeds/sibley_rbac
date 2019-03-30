<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Staff */

$this->title = 'Update Staff: ' . $model->first_name . ' ' . $model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Staff', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="staff-update">

    <h3 class="p-3"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'elected' => $elected,
        //'imgAssets' => $imgAssets
    ]) ?>

</div>
