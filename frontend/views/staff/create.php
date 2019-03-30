<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Staff */

$this->title = 'Create Staff Member';
$this->params['breadcrumbs'][] = ['label' => 'Staff', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-create">

<h3 class="p-3"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'elected' => $elected,
        //'imgAssets' => $imgAssets
    ]) ?>

</div>
