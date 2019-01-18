<?php
// YOUR_APP/views/test/test.php
//https://www.codevoila.com/post/37/yii2-listview-create-custom-linkpager-class-part2

use yii\widgets\ListView;
?>

<?= ListView::widget([
    'options' => [
        'tag' => 'div',
        'class' => 'pagination',
        'style' => 'display:inline-block;float:left;margin:20px 10px 20px 0;width:auto;'
    ],
    'dataProvider' => $listDataProvider,
    'itemView' => function ($model, $key, $index, $widget) {
        return '<div>' . $model['title'] . '</div>';
    },
    'itemOptions' => [
        'tag' => false,
    ],
    'summary' => '',
    'layout' => '{items} {pager}',
    
    'pager' => [
        // Use custom pager widget class
        'class' => frontend\widgets\mylinkpager\MyLinkPager::className(),
        
        // Configurations for default LinkPager widget are still available
        'firstPageLabel' => 'First',
        'lastPageLabel' => 'Last',
        'maxButtonCount' => 4,
    ],
    // Options for <ul> wrapper of default pager buttons are still available
    

    // Style for page size select
    //'sizeListHtmlOptions' => [
    //    'class' => 'form-control',
    //    'style' => 'display:inline-block;float:left;margin:20px 10px 20px 0;width:auto;'
    //],

    // Style for go to page input
    //'goToPageHtmlOptions' => [
    //    'class' => 'form-control',
    //    'style' => 'display:inline-block;float:left;margin:20px 10px 20px 0;width:auto;'
    //],
]);