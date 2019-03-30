<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BusinessWithCategories */
/* @var $category common\models\BusinessWithCategories */

$this->title = Yii::t('app', 'Create Organization');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Organizations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'categories' => $categories,
    'modelsContact' => $modelsContact
]) ?>