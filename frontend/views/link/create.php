<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Link */

$this->title = 'Create Link';
$this->params['breadcrumbs'][] = ['label' => 'Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="link-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'linkGroups'  => $linkGroups
    ]) ?>

</div>
