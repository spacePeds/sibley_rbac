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
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use frontend\models\ImageAsset;
use frontend\components\FrontendController;
use yii\helpers\Url;
//use bitcko\googlecalendar\GoogleCalendarApi;


/**
 * SibleyController implements the CRUD actions for Sibley model.
 */
class SibleyController extends FrontendController
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
     * Displays city of sibley page.
     *
     * @return mixed
     */
    public function actionCity() {
        //define semantic url for page
        $slug = '';
        $pageKey = 4;
        $page = $this->getGenericPage($pageKey);

        return $this->render('genericMaster', [
            'page' => $page,
            'key' => $pageKey,
        ]);
/*        
        //load sub-sections
        $subSections = SubPage::find()->where(['page_id' => $pageKey])->orderBy(['sort_order' => SORT_ASC])->asArray()->all();
        //append subsection documents (if any)
        foreach($subSections as $idx => $subSection) {
            $subSections[$idx]['documents'] = [];
            $documents = Document::find()->where(['table_record' => 'subPage_'.$subSection['id']])->asArray()->all();
            if (!empty($documents)) {
                $subSections[$idx]['documents'] = $documents;
            }
        }

        //load staff
        $staff = Staff::find()
            ->select([
                'staff.id','first_name','last_name','position','elected','email','phone', 'image',
                'DATE_FORMAT(staff_elected.term_start, "%c/%e/%Y") as termStartFmtd','DATE_FORMAT(staff_elected.term_end, "%c/%e/%Y") as termEndFmtd'
            ])
            ->leftJoin('staff_elected', '`staff_elected`.`staff_id` = `staff`.`id`')->asArray()->all();


        //load council meeting within the past month
        $monthAgo = date('Y-m-d', mktime(0, 0, 0, date('m')-1, date('d'), date('Y')));
        $tomorrow = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')+1, date('Y')));
        $meetings = Agenda::find()->select(['id','type','DATE_FORMAT(date, "%W %M %D") as fmtdDt'])->where(
            ['between', 'date', $monthAgo, $tomorrow ])->orderBy('date')->asArray()->all();

        return $this->render('city', [
            'page' => $page,
            'key' => $pageKey,
            'staff' =>$staff,
            'subSections' => $subSections,
            'meetings' => $meetings
        ]);
        */
    }
    /**
     * Displays sibley homepage.
     *
     * @return mixed
     */
    // public function actionStaff()
    // {
    //     $staff = Staff::find()
    //     ->select([
    //         'staff.id','first_name','last_name','position','elected','email','phone', 'image_asset',
    //         'DATE_FORMAT(staff_elected.term_start, "%c/%e/%Y") as termStartFmtd','DATE_FORMAT(staff_elected.term_end, "%c/%e/%Y") as termEndFmtd'
    //     ])
    //     ->leftJoin('staff_elected', '`staff_elected`.`staff_id` = `staff`.`id`')->asArray()->all();

    //     $imgAssets = ImageAsset::retrieveAssets();

    //     //link up any set images
    //     foreach ($staff as $idx => $person) {
    //         foreach ($imgAssets as $imgAsset) {
    //             if ($person['image_asset'] == $imgAsset['id'] && $person['image_asset'] != 0) {
    //                 $staff[$idx]['image'] = $imgAsset;
    //             }
    //         }
    //     }

    //     return $this->render('staff', [
    //         'model' => Page::find()->where(['id'=>4])->one(),
    //         'staff' => $staff
    //     ]);
    // }
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
                $e->start = date('Y-m-d', strtotime($event['start_dt']));
            }
            if (isset($event['end_dt'])) {
                //cut off time-stamp before comparison
                $date1 = new \DateTime(date("Y-m-d", strtotime($event['start_dt'])));
                $date2 = new \DateTime(date("Y-m-d", strtotime($event['end_dt'])));

                $interval = $date1->diff($date2);
                if ($date2 > $date1) {
                    //multi day event
                    $e->start = date('Y-m-d\TH:i:s\Z', strtotime($event['start_dt']));
                    $e->end = date('Y-m-d\TH:i:s\Z', strtotime($event['end_dt']));
                }
            }
            
            
            $e->nonstandard = [
                'description' => $event['description']
            ];
            if ($event['group'] == 'city') {
                $e->backgroundColor = Yii::$app->params['eventGroupColor']['city'];
            }
            if ($event['group'] == 'rec') {
                $e->backgroundColor = Yii::$app->params['eventGroupColor']['rec'];
            }
            if ($event['group'] == 'chamber') {
                $e->backgroundColor = Yii::$app->params['eventGroupColor']['chamber'];
            }
            if ($event['group'] == 'hol') {
                $e->backgroundColor = Yii::$app->params['eventGroupColor']['hol'];
            }
            $eventArr[] = $e;
            
        }

        // $Event = new \yii2fullcalendar\models\Event();
        // $Event->id = 1;
        // $Event->title = 'Testing';
        // $Event->start = date('Y-m-d\TH:i:s\Z');
        // $Event->nonstandard = [
        //     'field1' => 'Something I want to be included in object #1',
        //     'field2' => 'Something I want to be included in object #2',
        // ];
        // $eventArr[] = $Event;

        // $Event = new \yii2fullcalendar\models\Event();
        // $Event->id = 2;
        // $Event->title = 'Testing';
        // $Event->start = date('Y-m-d\TH:i:s\Z',strtotime('tomorrow 6am'));
        // $eventArr[] = $Event;

        // $Event = new \yii2fullcalendar\models\Event();
        // $Event->id = 3;
        // $Event->title = 'Testing Multi-Day';
        // $Event->start = date('Y-m-d\TH:i:s\Z',strtotime('tomorrow 6am'));
        // $Event->end = date('Y-m-d\TH:i:s\Z',strtotime('friday 12:30am'));
        // $eventArr[] = $Event;

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
        $page = $this->getGenericPage($pageKey);
        return $this->render('genericMaster', [
            'page' => $page,
            'key' => $pageKey,
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
        return $this->render('genericMaster', [
            'page' => $page,
            'key' => $pageKey,
        ]);
    }

    /**
     * Displays tutorials page.
     *
     * @return mixed
     */
    public function actionTutorial()
    {
        if (Yii::$app->user->can('view_admin')) {
            return $this->render('tutorial');
        } else {
            throw new ForbiddenHttpException('You do not have permission to access this page.');
        }
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

        return $this->render('genericMaster', [
            'page' => $page,
            'key' => $pageKey,
            
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
        //echo '<pre>' . print_r($page) . '</pre>';
/*
        $subSections = SubPage::find()->where(['page_id' => $pageKey])->orderBy(['sort_order' => SORT_ASC])->asArray()->all();
        //append subsection documents (if any)
        foreach($subSections as $idx => $subSection) {
            $subSections[$idx]['documents'] = [];
            $documents = Document::find()->where(['table_record' => 'subPage_'.$subSection['id']])->asArray()->all();
            if (!empty($documents)) {
                $subSections[$idx]['documents'] = $documents;
            }
        }
*/
        return $this->render('genericMaster', [
            'page' => $page,
            'key' => $pageKey,
            
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
        
        return $this->render('genericMaster', [
            'page' => $page,
            'key' => $pageKey,
            
        ]);
/*
        $subSections = SubPage::find()->where(['page_id' => $pageKey])->orderBy(['sort_order' => SORT_ASC])->asArray()->all();
        
        //append subsection documents (if any)
        foreach($subSections as $idx => $subSection) {
            $subSections[$idx]['documents'] = [];
            $documents = Document::find()->where(['table_record' => 'subPage_'.$subSection['id']])->asArray()->all();
            if (!empty($documents)) {
                $subSections[$idx]['documents'] = $documents;
            }
        }

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
            'page' => $page,
            'key' => $pageKey,
            'events' =>$enhancedEvents,
            'subSections' => $subSections
        ]);
    */
    }



    /**
     * TEst page
     */
    public function actionSpiritualCenters() {
        //define semantic url for page
        $slug = '';
        $pageKey = 15;
        $page = $this->getGenericPage($pageKey);

        return $this->render('genericMaster', [
            'page' => $page,
            'key' => $pageKey,
            
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
     * Load all recreation events for the current week
     * @return array
     */
    protected function loadRecEvents() {
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
        return $enhancedEvents;
    }
    /** 
     * Load all city council meetings within the past month
     * @return array
     */
    protected function loadCityMeetings() {
        //load council meeting within the past month
        $monthAgo = date('Y-m-d', mktime(0, 0, 0, date('m')-1, date('d'), date('Y')));
        $tomorrow = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')+1, date('Y')));
        $meetings = Agenda::find()->select(['id','type','DATE_FORMAT(date, "%W %M %D") as fmtdDt'])->where(
            ['between', 'date', $monthAgo, $tomorrow ])->orderBy('date')->asArray()->all();
        return $meetings;
    }

    /** 
     * Load city staff
     * @return array
     */
    protected function loadCityStaff() {
        $staff = Staff::find()
            ->select([
                'staff.id','first_name','last_name','position','elected','email','phone', 'image',
                'DATE_FORMAT(staff_elected.term_start, "%c/%e/%Y") as termStartFmtd','DATE_FORMAT(staff_elected.term_end, "%c/%e/%Y") as termEndFmtd'
            ])
            ->leftJoin('staff_elected', '`staff_elected`.`staff_id` = `staff`.`id`')->asArray()->all();
        return $staff;
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