<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Business */

$this->title = Yii::t('app', 'Update Business: ' . $model->name, [
    'nameAttribute' => '' . $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Businesses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

//echo '<pre>';
//echo print_r($model);
//echo print_r($modelsContactMethod);
//echo '</pre>';
?>

<?php
echo  $this->render('_form', [
    'model' => $model,
    'categories' => $categories,
    'modelsContact' => $modelsContact
]); 
?>