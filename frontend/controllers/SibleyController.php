<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Staff;
use common\models\User;
use frontend\models\Page;
use frontend\models\Business;
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
                if ($person['image_asset'] == $imgAsset['id'] && $person['image_asset'] != 0) {
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
     * Displays City map.
     *
     * @return mixed
     * @param integer
     */
    public function actionMap()
    {
        
        return $this->render('map', [
            
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
        //define semantic url for page
        $slug = '';
        $pageKey = 2;
        $page = Page::find()->where(['id'=>$pageKey])->one();
        if (empty($page)) {

        }
        return $this->render('location', [
            'details' => $page,
            'key' => $pageKey
        ]);
    }
    /**
     * Displays sibley generic restaurant page.
     *
     * @return mixed
     */
    public function actionFood()
    {
        //define semantic url for page
        $slug = '';
        $pageKey = 6;
        $page = $this->getGenericPage($pageKey);
        return $this->render('generic', [
            'details' => $page,
            'key' => $pageKey
        ]);
    }
    /**
     * Displays sibley generic lodging page.
     *
     * @return mixed
     */
    public function actionLodging()
    {
        //define semantic url for page
        $slug = '';
        $pageKey = 5;
        $page = $this->getGenericPage($pageKey);

        //echo '<pre>' . print_r($page, true) . '</pre>';
        //echo '<pre>' . print_r($organizations, true) . '</pre>';
        return $this->render('lodging', [
            'details' => $page,
            'key' => $pageKey
        ]);
    }

    /**
     * Displays sibley generic chamber page.
     *
     * @return mixed
     */
    public function actionChamber()
    {
        //define semantic url for page
        $slug = '';
        $pageKey = 7;
        $page = $this->getGenericPage($pageKey);

        //echo '<pre>' . print_r($page, true) . '</pre>';
        //echo '<pre>' . print_r($organizations, true) . '</pre>';
        return $this->render('generic', [
            'details' => $page,
            'key' => $pageKey
        ]);
    }
    /**
     * Displays sibley recreation department page.
     *
     * @return mixed
     */
    public function actionRecreation()
    {
        //define semantic url for page
        $slug = '';
        $pageKey = 9;
        $page = $this->getGenericPage($pageKey);

        $dayOfWeek = date('w');
        if ($dayOfWeek == 0) {
            $sunday = strtotime("today");
            $saturday = strtotime("next Saturday");
        } else if ($dayOfWeek == 6){    
            $sunday = strtotime("last sunday");
            $saturday = strtotime("today");
        } else {
            $sunday = strtotime("last sunday");
            $saturday = strtotime("next saturday");
        }
        $recEvents = Event::find()
            ->orderBy(['start_dt' => SORT_ASC])
            ->where(['group' => 'rec'])
            ->andWhere(['>=', 'start_dt', strftime('%Y-%m-%d', $sunday)])
            ->andWhere(['<=','start_dt', strftime('%Y-%m-%d', $saturday)])
            ->asArray()->all();
        $enhancedEvents = $this->injectRepeatingEvents($recEvents);
        
        //echo '<pre>Last Sunday:' . strftime('%Y-%m-%d', $sunday) . '</pre>';
        //echo '<pre>This Saturday:' . strftime('%Y-%m-%d', $saturday) . '</pre>';
        //echo '<pre>:' . print_r($enhancedEvents, true) . '</pre>';
        return $this->render('rec', [
            'details' => $page,
            'key' => $pageKey,
            'events' =>$enhancedEvents 
        ]);
    }

    /**
     * Displays sibley generic chamber member benefits page.
     *
     * @return mixed
     */
    public function actionChamberBenefits()
    {
        //define semantic url for page
        $slug = '';
        $pageKey = 8;
        $page = $this->getGenericPage($pageKey);

        //echo '<pre>' . print_r($page, true) . '</pre>';
        //echo '<pre>' . print_r($organizations, true) . '</pre>';
        return $this->render('generic', [
            'details' => $page,
            'key' => $pageKey
        ]);
    }

    /**
     * Construct array of retrieved page elements
     * @param integer $pageKey
     * @return array
     */
    protected function getGenericPage($pageKey) {
        //$page = Page::find()->joinWith('page_category')->where(['page_category.page_id'=>$pageKey])->all();   //should work :(
        $page = Page::find()
            ->select('page.*, page_category.*')
            ->leftJoin('page_category', '`page_category`.`page_id` = `page`.`id`')
            ->where(['page.id'=>$pageKey])->asArray()
            ->one();
        
        if (isset($page['last_edit_dt'])) {
            $page['last_edit_dt'] = date("m/d/Y @ g:ia", strtotime($page['last_edit_dt']));
            $page['linkedOrganizations'] = [];
        }    
        //load all business with found categories
        //$business = Business::find()->joinWith(['business_category','contact_method'])->where(['business_category.category_id' => $page['category_id']])->asArray()->all();
        $organizations = [];
        if (!empty($page['category_id'])) {
            $organizations = Business::find()
                ->select('business.id as bid, business.*, business_category.*, contact_method.*')
                ->leftJoin('business_category', '`business_category`.`business_id` = `business`.`id`')
                ->leftJoin('contact_method', '`contact_method`.`business_id` = `business`.`id`')
                ->where(['business_category.category_id' => $page['category_id']])->asArray()->all();
        }
        

        
        foreach ($organizations as $organization) {
            $bid = $organization['bid'];
            $page['linkedOrganizations'][$bid]['name'] = $organization['name'];
            $page['linkedOrganizations'][$bid]['address1'] = $organization['address1'];
            $page['linkedOrganizations'][$bid]['address2'] = $organization['address2'];
            $page['linkedOrganizations'][$bid]['city'] = $organization['city'];
            $page['linkedOrganizations'][$bid]['state'] = $organization['state'];
            $page['linkedOrganizations'][$bid]['zip'] = $organization['zip'];
            $page['linkedOrganizations'][$bid]['url'] = $organization['url'];
            $page['linkedOrganizations'][$bid]['note'] = $organization['note'];
            $page['linkedOrganizations'][$bid]['member'] = $organization['member'];
            $page['linkedOrganizations'][$bid]['contact'][] = [
                'method' => $organization['method'],
                'contact'=> $organization['contact'],
                'description' => $organization['description']
            ];
        }
        return $page;
    }

    /**
     * Generate additional events based on repeating parameter
     * REMOVE THIS and move reference to components/frontedncontroller
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