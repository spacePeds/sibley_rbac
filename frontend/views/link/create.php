<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Link */

$this->title = 'Create Quick-Link';
$this->params['breadcrumbs'][] = ['label' => 'Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="link-create">

    <h3 class="ml-3"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'linkGroups'  => $linkGroups
    ]) ?>

</div>
