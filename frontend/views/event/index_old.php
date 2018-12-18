<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$dayClick = <<<EOF
    function(date, jsEvent, view, resource) {
        var dt = date.format('YYYY-MM-DD');
        console.log( 'I clicked on a day', dt );
        $.get('/doingiteasy/public_html/admin/event/create',{'date':dt})
            .done(function(data){
                $('.modal').modal('show')
                    .find('#modalContent')
                    .html(data);
        });
    }
EOF;
Html::encode($dayClick);
$eventClick = <<<EOF
    function(calEvent, jsEvent, view) {
        console.log('Event: ',calEvent.id, "view:", view.name); 
        console.log('Coordinates: ', jsEvent.pageX,jsEvent.pageY); 

        $.ajax({
            url: "/doingiteasy/public_html/admin/event/update",
            data: {'id':calEvent.id},
            method: "get",
        }).done(function(data) {
            console.log(data);
            $('#modal').modal('show').find('#modalContent').html(data);
            $('#modal').find('.modal-header').find('h4').html('Updating Event');
        }).fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            console.log(jqXHR);
        });
        
    }
EOF;
Html::encode($eventClick);
$eventDrop = <<<EOF
    function(event, delta, revertFunc, jsEvent, ui, view) {
        console.log(event);
        if (!confirm("Are you sure about this change?")) {
            revertFunc();
        } else {
            var startDt = '';
            var endDt = '';
            if (event.start !== null) {
                startDt = event.start.format('YYYY-MM-DD');
            }
            if (event.end !== null) {
                endDt = event.end.format('YYYY-MM-DD');
            }
            $.ajax({
                url: "/doingiteasy/public_html/admin/event/update_ajax",
                data: {'id':event.id, 'startDate': startDt, 'endDate': endDt },
                method: "post",
                dataType: "json"
            }).done(function(data) {
                if (data.status !== 'success') {
                    alert('An error occured during time shift.');
                    revertFunc();
                }
                //$.parseJSON()
                console.log(data);
            }).fail(function( jqXHR, textStatus ) {
                alert( "Request failed: " + textStatus );
                console.log(jqXHR);
                revertFunc();
            });
        }

    }
EOF;
Html::encode($eventDrop);



$this->title = 'Events';
$this->params['breadcrumbs'][] = $this->title;

//foreach(Yii::app()->user->getFlashes() as $key => $message) {
//    echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
//}
?>
<div class="section">

    <h1><?= Html::encode($this->title) ?></h1>
    

    <?php
    /**/
        echo \yii2fullcalendar\yii2fullcalendar::widget(array(
            'events' => $events,
            'options' => [
                'id' =>'eventCalendar'
            ],
            'clientOptions' => [ 
                'selectable' => true,
                'editable' => true,
                'eventLimit'=> true,
                'dayClick' => new JSExpression($dayClick),
                'eventDrop' => new JSExpression($eventDrop),
                'eventClick' => new JSExpression($eventClick),
            ],
            'header' => [
                'center'=>'title',
                'left'=>'prev,next today',
                'right'=>'month,agendaWeek,agendaDay,listWeek'
            ]
        ))
    
    ?>
    <div id="eventCalendar2"></div>
</div>

<?php
Modal::begin([
    'header'    => '<h4>New Event</h4>',
    'id'        => 'modal',
    'size'      => 'modal-lg'
]);
echo '<div id="modalContent"></div>';
Modal::end();

