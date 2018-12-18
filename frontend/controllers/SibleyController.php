<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Staff;
use common\models\User;
use frontend\models\Page;
use frontend\models\Event;
use frontend\models\Agenda;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use frontend\models\ImageAsset;

/**
 * SibleyController implements the CRUD actions for Sibley model.
 */
class SibleyController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]  
        ];
    }
    /**
     * Displays sibley homepage.
     *
     * @return mixed
     */
    public function actionStaff()
    {
        $staff = Staff::find()
        ->select([
            'staff.id','first_name','last_name','position','elected','email','phone', 'image_asset',
            'DATE_FORMAT(staff_elected.term_start, "%c/%e/%Y") as termStartFmtd','DATE_FORMAT(staff_elected.term_end, "%c/%e/%Y") as termEndFmtd'
        ])
        ->leftJoin('staff_elected', '`staff_elected`.`staff_id` = `staff`.`id`')->asArray()->all();

        $imgAssets = ImageAsset::retrieveAssets();

        //link up any set images
        foreach ($staff as $idx => $person) {
            foreach ($imgAssets as $imgAsset) {
                if ($person['image_asset'] == $imgAsset['id']) {
                    $staff[$idx]['image'] = $imgAsset;
                }
            }
        }

        return $this->render('staff', [
            'model' => Page::find()->where(['id'=>4])->one(),
            'staff' => $staff
        ]);
    }
    /**
     * Displays City Council Meeting Agendas and Minutes.
     *
     * @return mixed
     * @param integer
     */
    public function actionCouncil($id=0)
    {
        $model = new Agenda();
        $model->yearList = $this->getYearRange();
        $model->yearToggle = date('Y');
        $model->dfltAgenda = $id;

        //echo 'id:' . $id;
        
        return $this->render('council', [
            'model' => $model
        ]);
    }
    /**
     * Displays City wide calendar.
     *
     * @return mixed
     */
    public function actionCalendar()
    {
        $calendar = [
            'canEdit' => false,
            'canDrag' => false,
        ];
        if (Yii::$app->user->can('update_event')) {
            $user_id = User::findByUsername(Yii::$app->user->identity->username)->getId();
            $calendar['canEdit'] = true;
            $calendar['canDrag'] = true;
            //$role = \Yii::$app->authManager->getRolesByUser($user_id);
        }
        //+,- 2 years
        $twoYrsAgo = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y')-2));
        $twoYrsFut = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y')+2));
        $events = Event::find()->where(['>=', 'start_dt', $twoYrsAgo])->andWhere(['<=','start_dt', $twoYrsFut])->all();
        $enhancedEvents = $this->injectRepeatingEvents(ArrayHelper::toArray($events));
        $eventArr = [];
        //echo '<pre>' . print_r($enhancedEvents,true) . '</pre>';
        foreach ($enhancedEvents as $event) 
        {
            
/**/            
            //https://fullcalendar.io/docs/event-object
            $e = new \yii2fullcalendar\models\Event();
            $e->id = $event['id'];
            $e->title = $event['subject'];
            $e->start = $event['start_dt'];

            //is all day event?
            if ($event['all_day']) {
                $e->allDay = 1;
                //cut off time-stamp
                $e->start = date("Y-m-d", strtotime($event['start_dt']));
            }
            if (isset($event['end_dt'])) {
                //cut off time-stamp before comparison
                $date1 = new \DateTime(date("Y-m-d", strtotime($event['start_dt'])));
                $date2 = new \DateTime(date("Y-m-d", strtotime($event['end_dt'])));

                $interval = $date1->diff($date2);
                if ($date2 > $date1) {
                    //multi day event
                    $e->start = date("Y-m-d", strtotime($event['start_dt']));
                    $e->end = date("Y-m-d", strtotime($event['end_dt']));
                }
            }
            
            
            $e->nonstandard = [
                'description' => $event['description']
            ];
            if ($event['group'] == 'city') {
                $e->backgroundColor = 'green';
            }
            if ($event['group'] == 'rec') {
                $e->backgroundColor = 'gray';
            }
            if ($event['group'] == 'chamber') {
                $e->backgroundColor = 'pink';
            }
            $eventArr[] = $e;
            
        }

        return $this->render('calendar', [
            'events' => $eventArr,
            'calendar' => $calendar
        ]);
    }
    /**
     * Displays sibley generic location page.
     *
     * @return mixed
     */
    public function actionLocation()
    {

        return $this->render('location', [
            'details' => Page::find()->where(['id'=>2])->one()
        ]);
    }
    /**
     * Generate additional events based on repeating parameter
     * @param array $events
     * @return array
     */
    protected function injectRepeatingEvents($events) {
        $eventList = [];
        foreach ($events as $event) 
        {
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
                'all_day' => $event['all_day'],
                'start_dt' => $date->format("Y-m-d H:i:s"),
            ];
        }
        return $eventList;
    }
    /**
     * https://stackoverflow.com/questions/3028491/php-weeks-between-2-dates
     * @param
     * @param
     * @return integer
     * */
    protected function datediffInWeeks($date1, $date2)
    {
        if($date1 > $date2) return datediffInWeeks($date2, $date1);
        $first = DateTime::createFromFormat('m/d/Y', $date1);
        $second = DateTime::createFromFormat('m/d/Y', $date2);
        return floor($first->diff($second)->days/7);
    }
    /**
     * Define a range of years and load list into array
     * 
     */
    protected function getYearRange() {
        $yearList = [];
        $current_year = date('Y');
        $latest_year = $current_year + 1;
        $earliest_year = $current_year - 5; //might want to revise based on data returned from DB
        foreach ( range( $latest_year, $earliest_year ) as $i ) {
           $yearList[$i] = $i;
        }
        return $yearList;
    }
}