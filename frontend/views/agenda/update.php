<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Agenda */

$this->title = Yii::t('app', 'Update Agenda: ' . $model->id, [
    'nameAttribute' => '' . $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agendas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="agenda-update">

    <?= $this->renderAjax('_form', [
        'model' => $model,
    ]) ?>

</div>
