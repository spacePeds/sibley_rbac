<?php    
//Create a universal component
//inject a layer into the heirarchy

namespace frontend\components;
use Yii;
use frontend\models\Event;
use frontend\models\Page;
use frontend\models\Business;
use frontend\models\HeaderImage;

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
                    'groupDesc' => Yii::$app->params['eventGroups'][$event['group']],
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
                'groupDesc' => Yii::$app->params['eventGroups'][$event['group']],
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
                'groupDesc' => isset($event['groupDesc']) ? $event['groupDesc'] : '',
                'color' => isset($event['color']) ? $event['color'] : '',
                'icon' => isset($event['icon']) ? $event['icon'] : '',
                'all_day' => $event['all_day'],
                'startTime' => $startTime,
                'attachment' => $attachment
            ];
        }
        return $eventList;
    }

    /**
     * Construct array of retrieved page elements
     * @param integer $pageKey
     * @return array
     */
    public function getGenericPage($pageKey) {
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

        //load any header images
        $page['headerImages'] = [];
        $headImages = HeaderImage::find()->where(['image_idx'=>'page_'.$pageKey])->asArray()->all();
        foreach($headImages as $headImage) {
            $page['headerImages'][] = $headImage;
        }
        return $page;
    }
}