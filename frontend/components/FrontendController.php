<?php    
//Create a universal component
//inject a layer into the heirarchy

namespace frontend\components;
use Yii;
use frontend\models\Event;

class FrontendController extends \yii\web\Controller
{
    public function init(){
        parent::init();

    }
    public function Hello(){
        return "Hello Yii2";
    }

    /**
     * Generate additional events based on repeating parameter
     * @param array $events
     * @return array
     */
    public function injectRepeatingEvents($events) {
        $eventList = [];
        foreach ($events as $event) 
        {
            //append any attachments
            $pdfFileInfo = Event::getAttachment($event['id']);
            if (!empty($pdfFileInfo)) {
                $event['attachment'] = Yii::getAlias('@web') .'/'. $pdfFileInfo['path'] . $pdfFileInfo['name'];
            }
            //if (isset($event['group'])) {
            //    $event['color'] = Yii::$app->params['eventGroupColor'][$event['group']];
            //}

            //anchor day for recurrance
            switch ($event['repeat_interval']) {
                case 1: //weekly
                    $newEvents =  $this->buildWeeklyEvents($event);
                    foreach ($newEvents as $newEvent) {
                        $eventList[] = $newEvent;
                    }
                    break;
                case 2: //bi-weekly
                    $newEvents =  $this->buildWeeklyEvents($event,true);
                    foreach ($newEvents as $newEvent) {
                        $eventList[] = $newEvent;
                    }
                    //$eventList[] = $event;
                    break;
                case 3: //monthly
                    $newEvents =  $this->buildRepeatingEvents($event,'P1M');
                    foreach ($newEvents as $newEvent) {
                        $eventList[] = $newEvent;
                    }
                    break;
                case 4: //annual
                    $newEvents =  $this->buildRepeatingEvents($event,'P1Y');
                    foreach ($newEvents as $newEvent) {
                        $eventList[] = $newEvent;
                    }
                    break;
                default:
                    $eventList[] = $event;
            }
        }
        return $eventList;
    }
    /**
     * Create array of weekly events
     * @param array event
     * @param boolean $isBi
     * @return array
     */
    protected function buildWeeklyEvents($event,$isBi=false) {
        $eventList = [];
        $dayOfWeek = date("N", strtotime($event['start_dt']));
        $begin = new \DateTime( $event['start_dt'] );
        $end = new \DateTime( $event['end_dt'] );
        //$end = $end->modify( '+1 day' ); 

        //http://php.net/manual/en/dateinterval.construct.php
        $interval = new \DateInterval('P1D');   //Period - 1 day
        $daterange = new \DatePeriod($begin, $interval ,$end);
        
        $cnt = 1;
        foreach($daterange as $date){
            //echo '<br>dayogweek:' . $dayOfWeek . ', date: ' . $date->format("Y-m-d H:i:s") .', day: ' .$date->format('N');
            if ($date->format('N') == $dayOfWeek) {
                if ($isBi && $cnt%2 == 0) {
                    //skip
                    $cnt++;
                    continue;
                }
                //create event
                //$e = new Event();
                //$e->id = $event->id;
                //$e->subject = $event->subject;
                //$e->group = $event->group;
                //$e->all_day = $event->all_day;
                //$e->start_dt = $date->format("Y-m-d H:i:s");
                //$eventList[] = ArrayHelper::toArray($e);

                $eventList[] = [
                    'id' => $event['id'],
                    'subject' => $event['subject'],
                    'description' => $event['description'],
                    'group' => $event['group'],
                    'color' => Yii::$app->params['eventGroupColor'][$event['group']],
                    'icon' => Yii::$app->params['eventGroupIcon'][$event['group']],
                    'all_day' => $event['all_day'],
                    'start_dt' => $date->format("Y-m-d H:i:s"),
                ];
                $cnt++;
            }
        }

        return $eventList;
    }
    /**
     * Create array of repeating events
     * http://php.net/manual/en/dateinterval.construct.php
     * @param array $event
     * @param string $intervalCode
     */
    protected function buildRepeatingEvents($event, $intervalCode) {
        $eventList = [];
        $dayOfYear = date("md", strtotime($event['start_dt']));
        $begin = new \DateTime( $event['start_dt'] );
        $end = new \DateTime( $event['end_dt'] );

        $interval = new \DateInterval($intervalCode);   
        $daterange = new \DatePeriod($begin, $interval ,$end);
        
        $cnt = 0;
        foreach($daterange as $date){
            $cnt++;
            $eventList[] = [
                'id' => $event['id'],
                'subject' => $event['subject'],
                'description' => $event['description'],
                'group' => $event['group'],
                'color' => Yii::$app->params['eventGroupColor'][$event['group']],
                'icon' => Yii::$app->params['eventGroupIcon'][$event['group']],
                'all_day' => $event['all_day'],
                'start_dt' => $date->format("Y-m-d H:i:s"),
            ];
        }
        return $eventList;
    }
    /**
     * Sort the event array by date
     * @param array $events
     * @return array
     */
    public function groupEventsByDate($events) {
        $eventList = [];
        foreach ($events as $event) 
        {
            $dtFmtd = date('l F jS', strtotime($event['start_dt']));
            $dtSrt = date('Ymd', strtotime($event['start_dt']));
            $attachment = isset($event['attachment']) ? $event['attachment'] : '';
            $startTime = ($event['all_day']) ? '' : date('g:ia', strtotime($event['start_dt']));
            $eventList[$dtSrt][] = [
                'formatted' => $dtFmtd,
                'id' => $event['id'],
                'subject' => $event['subject'],
                'description' => $event['description'],
                'group' => $event['group'],
                'color' => isset($event['color']) ? $event['color'] : '',
                'icon' => isset($event['icon']) ? $event['icon'] : '',
                'all_day' => $event['all_day'],
                'startTime' => $startTime,
                'attachment' => $attachment
            ];
        }
        return $eventList;
    }
}