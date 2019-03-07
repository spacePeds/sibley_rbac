<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\views\MyActionColumn;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BusinessSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

function formatPhoneNumber($phoneNumber) {
    $phoneNumber = preg_replace('/[^0-9]/','',$phoneNumber);

    if(strlen($phoneNumber) > 10) {
        $countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-10);
        $areaCode = substr($phoneNumber, -10, 3);
        $nextThree = substr($phoneNumber, -7, 3);
        $lastFour = substr($phoneNumber, -4, 4);

        $phoneNumber = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour;
    }
    else if(strlen($phoneNumber) == 10) {
        $areaCode = substr($phoneNumber, 0, 3);
        $nextThree = substr($phoneNumber, 3, 3);
        $lastFour = substr($phoneNumber, 6, 4);

        $phoneNumber = '('.$areaCode.') '.$nextThree.'-'.$lastFour;
    }
    else if(strlen($phoneNumber) == 7) {
        $nextThree = substr($phoneNumber, 0, 3);
        $lastFour = substr($phoneNumber, 3, 4);

        $phoneNumber = $nextThree.'-'.$lastFour;
    }

    return $phoneNumber;
}

$this->title = Yii::t('app', 'Organizations');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->user->can('create_business')) : ?>
    <div class="adminFloater shadow-sm p-3 mb-5 bg-white rounded d-flex flex-column text-center">
        <div class="p-2"><?= Html::a(Yii::t('app', 'Create Business'), [Url::to('/business/create')], ['class' => 'btn btn-success']) ?></div>
        <?php if (Yii::$app->user->can('update_category')) : ?>
            <div class="p-2"><?= Html::a(Yii::t('app', 'Update Categories'), [Url::to('/category')], ['class' => 'btn btn-success']) ?></div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="business-index container">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            // Customzing options for pager container tag
            //'options' => [
            //    'tag' => 'div',
            //    'class' => 'pager-wrapper',
            //    'id' => 'pager-container',
            //],
            'disabledListItemSubTagOptions' => [
                'tag' => 'a',
                'class' => 'page-link'
            ],
            // Customzing CSS class for pager link
            'linkOptions' => [
                'class' => 'page-link',
                
            ],
            'activePageCssClass' => 'page-item active',
            'disabledPageCssClass' => 'page-item disabled',
            
            // Customzing CSS class for navigating link
            'prevPageCssClass' => 'page-item',
            'nextPageCssClass' => 'page-item',
            'firstPageCssClass' => 'page-item',
            'lastPageCssClass' => 'page-item',
            //labels
            //'firstPageLabel' => '&nbsp;',
            //'lastPageLabel' => '&nbsp;',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            [
                'format' => 'image',
                //'value' => function($data) { return '/'.Yii::$app->params['orgImagePath'] . $data->image; },
                'content' => function($data){
                    $url = '/'.Yii::$app->params['orgImagePath'] . $data->image;
                    if (!empty($data->image)) {
                        return Html::img($url, ['alt'=>'yii','height'=>'100']);
                    }
                    return '';
                }
                
            ],           
            [
                'attribute' => 'name',
                'content' => function ($model) {
                    if (!empty($model->url)) {
                        return '<a target="_blank" href="'.$model->url.'">'.$model->name.'</a>';
                    }
                    return $model->name;
                }
                
            ],
            'fullAddress',
            //'address2',
            'city',
            //'state',
            //'zip',
            //'url:url',
            //'note:ntext',
            //'member',
            //'created_dt',
            //[
            //    'attribute' => 'contacts',
            //    'value' => 'contactMethods.description',
            //],
            [
                'label' => 'Contacts',
                'format' => 'raw',
                'attribute' => 'contacts',
                'value' => function ($data) {
                    $contacts = [];
                    foreach ($data->contactMethods as $record) {
                        if ($record['method'] == 'email') {
                            $contacts[] = '<a href="mailto:'.$record['contact'].'">'.$record['description'].'</a>';
                        }
                        if ($record['method'] == 'phone') {
                            $desc = '';
                            if (!empty($record['description'])) {
                                $desc = ' - ' . $record['description'];
                            }
                            
                            $contacts[] = formatPhoneNumber($record['contact']) . $desc;
                        }
                    }  
                    //print_r($data->contactMethods,true);
                    return implode('<br>', $contacts);
                }
            ],
            // 'business.contact_method.method',
            ['class' => 'frontend\views\MyActionColumn'],
        ],
    ]); ?>
</div>
