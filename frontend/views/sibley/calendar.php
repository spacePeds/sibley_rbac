<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap4\Modal;
use yii\web\JsExpression;
use yii\helpers\Url;
use frontend\assets\CalendarAdminAsset;
use frontend\assets\CalendarStandardAsset;
use frontend\assets\IcsAsset;

$siteRoot = ''; //Url::to('@web');
IcsAsset::register($this);
if (Yii::$app->user->can('update_event')) {
    CalendarAdminAsset::register($this);
    $clickEventAction = $siteRoot . "/event/update";
} else {
    CalendarStandardAsset::register($this);
    $clickEventAction = $siteRoot . "/event/view";
}
$dayClick = '
    function(date, jsEvent, view, resource) {
        Cal.dayClick(date, jsEvent, view, resource, \'' . $siteRoot . '\');
    }
';
Html::encode($dayClick);

$eventClick = <<<EOF
    function(calEvent, jsEvent, view) {
        Cal.eventClick(calEvent, jsEvent, view, '$siteRoot');
    }
EOF;
Html::encode($eventClick);
$eventDrop = <<<EOF
    function(event, delta, revertFunc, jsEvent, ui, view) {
        Cal.eventDrop(event, delta, revertFunc, jsEvent, ui, view,'$siteRoot');
    }
EOF;
Html::encode($eventDrop);
$eventResize = <<<EOF
    function(event, delta, revertFunc) {
        Cal.eventResize(event, delta, revertFunc, '$siteRoot');
    }
EOF;
Html::encode($eventResize);

$eventRender = <<<EOF
function(event, element, view){
    /*
    if (event.ranges !== undefined) {
        return (event.ranges.filter(function(range){
            return (event.start.isBefore(range.end) &&
                    event.end.isAfter(range.start));
        }).length)>0;
    } else {
        return '';
    }
    */
}
EOF;
Html::encode($eventRender);

$dayRender = <<<EOF
function(min_date, max_date, date, cell){
    
}
EOF;
Html::encode($dayRender);

//DEBUG
//echo '<pre>' . print_r($events,true) . '</pre>';

$this->title = 'Events';
$this->params['breadcrumbs'][] = $this->title;

//foreach(Yii::app()->user->getFlashes() as $key => $message) {
//    echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
//}
?>
<!-- paralex calendar -->
<section id="calendar-heading" class="p-5">
    <div class="dark-overlay">
        <div class="row">
        <div class="col">
            <div class="container pt-5">
            </div>
        </div>
        </div>
    </div>
</section>

<div class="section m-2">

    <h1><?= Html::encode($this->title) ?></h1>
    

    <?php
    /**/
        echo \yii2fullcalendar\yii2fullcalendar::widget(array(
            'themeSystem' => 'bootstrap4',
            'events' => $events,
            'options' => [
                'id' =>'eventCalendar',
                
            ],
            /*
            'eventSource' => [
                'googleCalendarApiKey' => 'AIzaSyD5pMcFdV9gFQHlY-rzl_vDMJl_r-wQPzM',
                'url' => 'https://www.googleapis.com/calendar/v3/calendars/usa__en%40holiday.calendar.google.com/events?key=xxx',
                'className' => 'gcal-event', 
                'currentTimezone' => 'America/Chicago'
            ],
            */
            'clientOptions' => [ 
                'selectable' => true,
                'editable' => $calendar['canEdit'],
                'eventLimit'=> true,
                'eventStartEditable' => $calendar['canDrag'],
                'nextDayThreshold' => '01:00:00',
                'timezone' => 'local',
                'dayClick' => new JSExpression($dayClick),
                'eventDrop' => new JSExpression($eventDrop),
                'eventClick' => new JSExpression($eventClick),
                'dayRender' => new JSExpression($dayRender),
                'eventRender' => new JSExpression($eventRender),
                'eventResize' => new JSExpression($eventResize),
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
