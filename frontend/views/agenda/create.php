<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Agenda */

$this->title = Yii::t('app', 'Create Agenda');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agendas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agenda-create">

    <?= $this->renderAjax('_form', [
        'model' => $model,
    ]) ?>

</div>
