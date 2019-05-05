<?php
/**
 * Console application to load Events
 * https://developers.google.com/calendar/v3/reference/events
 * ./yii google
 */

// commands/GoogleController.php DEMO
namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\base\Exception;
use Google_Client;
use Google_Service_Calendar;
use frontend\models\Event;


class GoogleController extends Controller
{
    /**
     * Two Months ago
     */
    private function getStartRange() {
        return date('Y-m-d', mktime(0, 0, 0, date('m')-1, date('d'), date('Y')));
    } 
    /**
     * One year into the future
     */
    private function getEndRange () {
        return date('Y-m-d', mktime(0, 0, 0, date('m')+2, date('d'), date('Y')));
    } 
    
    /**
     * Default
     */
    public function actionIndex()
    {
        if (php_sapi_name() != 'cli') {
            throw new Exception('This application must be run on the command line.');
            Controller::EXIT_CODE_ERROR;
        }
    
        echo "I am a test\n";
        echo Yii::getAlias('@frontend/config/credentials.json') . "\n";
        Controller::EXIT_CODE_NORMAL;
    }

    public function actionLoadHolidays()
    {
        if (php_sapi_name() != 'cli') {
            throw new Exception('This application must be run on the command line.');
            Controller::EXIT_CODE_ERROR;
        }
        
        echo "Load Holiday test\n";
        $client = $this->getClient();
        $service = new Google_Service_Calendar($client);

        $calendarId = 'en.usa#holiday@group.v.calendar.google.com';
        $optParams = array(
        'maxResults' => 25,
        'orderBy' => 'startTime',
        'singleEvents' => true,       //breakout repeating events
        'timeMin' => date('c'),       //ISO dt (2004-02-12T15:19:21+00:00)
        );
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        if (empty($events)) {
            print "No upcoming events found.\n";
        } else {
            print "Upcoming events:\n";

            $today = date('Y-m-d');
            $oneYrFut = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y')+1));
            $existingEvents = Event::find()
                ->where(['>=', 'start_dt', $today])
                ->andWhere(['<=','start_dt', $oneYrFut])
                ->andWhere(['group' => 'hol'])
                ->asArray()->all();
            print_r($existingEvents);

            //loop through google holidays
            foreach ($events as $event) {
                $start = $event->start->dateTime; 
                $summary = $event->getSummary();     
                if (empty($start)) {
                    $start = $event->start->date;
                }
                $idxSub = array_search($summary, array_column($existingEvents, 'subject'));
                if ($idxSub !== false) {
                    print "Found a match at index $idxSub \n";
                    //do dates match?
                    if (strtotime($start) == strtotime($existingEvents[$idxSub]['start_dt'])) {
                        echo "Dates match, Skip!\n";
                        continue;
                    }
                }

                //create event
                $model = new Event();
                $model->subject = $summary;
                $model->start_dt = date("Y-m-d", strtotime($start));
                $model->last_edit_dt = date('Y-m-d H:i:s');
                $model->end_dt = $model->start_dt;
                $model->user_id = 1; //assume admin
                $model->group = 'hol';
                $model->all_day = 1;
                $model->repeat_interval = 0;
                $model->save(false);
                printf("%s - %s\n", $event->getSummary(), $start);
               
            }
        }
        echo "done!\n";
        Controller::EXIT_CODE_NORMAL;
    }

    public function actionLoadRecEvents()
    {
        if (php_sapi_name() != 'cli') {
            throw new Exception('This application must be run on the command line.');
            Controller::EXIT_CODE_ERROR;
        }
        
        echo "Load Rec Events\n";
        $client = $this->getClient();
        $service = new Google_Service_Calendar($client);
        $fmtdEvents = [];

        $calendarId = 'sibley.rec@gmail.com';
        $optParams = array(
            'maxResults' => 50,
            'orderBy' => 'updated',
            'timeMin' => date('c', mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))),
            'timeMax' => date('c', mktime(0, 0, 0, date('m')+2, date('d'), date('Y')))
        );
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        foreach ($events as $event) {
            $recurrences = [];
            $rule = [];
            
            if (!empty($event->recurrence)) {
                $recurrences = $event->recurrence;
                foreach ($recurrences as $recurrence) {
                    $rrules = explode(':',$recurrence)[1];
                    $rule[] = explode(';',$rrules);
                }
                $fmtdGooEvents[] = $this->formatEvent($event, $rule);
            } else {
                $fmtdGooEvents[] = $this->formatEvent($event);
            }  
        }

        //load possible existing clashing events
        $timeMin = $this->getStartRange();
        $timeMax = $this->getEndRange();
        $existingEvents = Event::find()
            ->where(['>=', 'start_dt', $timeMin])
            ->andWhere(['<=','start_dt', $timeMax])
            ->andWhere(['group' => 'rec'])
            ->andWhere(['not', ['googleId' => null]])
            ->asArray()->all();
        
        //echo "existing Events:\n";
        //print_r($existingEvents);

        //echo "google events:\n";
        //print_r($fmtdGooEvents);
        
        $this->syncEvents($fmtdGooEvents, $existingEvents);

        echo "done!\n";
        Controller::EXIT_CODE_NORMAL;

    }
    /**
     * add/replace/delete as necessary
     * @param array $googleEvents
     * @param array $sibleyEvents
     */
    protected function syncEvents ($googleEvents, $sibleyEvents) {
        
        foreach ($googleEvents as $googleEvent) {
            if (empty($googleEvent)) {
                //skip
                continue;
            }

            echo $googleEvent['googleId'] . "\n";
            //match on google id
            $gIdx = array_search($googleEvent['googleId'], array_column($sibleyEvents, 'googleId'));
            if ($gIdx !== false) {
                print "Matched an existing google event at idx: $gIdx, does it need updating?\n";
                if (strtotime($googleEvent['startDt']) != strtotime($sibleyEvents[$gIdx]['start_dt'])
                    || strtotime($googleEvent['endDt']) != strtotime($sibleyEvents[$gIdx]['end_dt'])
                    || $googleEvent['subject'] != $sibleyEvents[$gIdx]['subject']) {
                    
                    //update the event
                    $this->updateEvent($googleEvent, $sibleyEvents[$gIdx]);
                }

                if ($googleEvent['status'] == 'cancelled') {
                    //remove the event
                    $this->deleteEvent($sibleyEvents[$gIdx]['id']);
                } else {
                    //nothing to update
                    echo "Noting to update.\n";

                }
            } else {
                //double check event doesn't alreeady exist
                //echo 'create: ' . $googleEvent['googleId'] . "?\n";
                //var_dump($googleEvent);
                //var_dump($sibleyEvents);
                $idxSub = array_search($googleEvent['subject'], array_column($sibleyEvents, 'subject'));
                if ($idxSub !== false) {
                    print "Found a subject match at index $idxSub \n";
                    //do dates match?
                    if (strtotime($googleEvent['startDt']) == strtotime($existingEvents[$idxSub]['start_dt'])) {
                        echo "Dates match, Skip!\n";
                        //false positive
                        continue;
                    }
                }

                //make sure google isin't feeding me events outside search criteria
                if (strtotime($googleEvent['startDt']) < strtotime($this->getStartRange())
                    || strtotime($googleEvent['startDt']) > strtotime($this->getEndRange())) {
                    
                    //echo "Event ".$googleEvent['googleId']." outside range! " . $googleEvent['startDt'] . "\n";
                    //for some reason google returned an event outside of range
                    continue;
                }
                
                
                //create event,
                $this->createEvent($googleEvent);
            }
        }       
    }
    /**
     * Update an event
     * @param array $googleEvent
     * @param array $sibleyEvent
     */
    protected function updateEvent ($googleEvent, $sibleyEvent) {
        echo 'looking up event: ' . $sibleyEvent['id'] . "\n";
        $eventId = $sibleyEvent['id'];
        $model = Event::findOne($eventId);
        $model->subject = $googleEvent['subject'];
        $model->start_dt = $googleEvent['startDt'];
        $model->last_edit_dt = date('Y-m-d H:i:s');
        $model->end_dt = $googleEvent['endDt'];;
        $model->all_day = $googleEvent['allDay'];
        $model->repeat_interval = $googleEvent['repeat'];
        $model->repeat_days = $googleEvent['repeatDays'];
        $model->save(false);
        echo "Event $eventId was updated.\n";
    }
    /**
     * Delete an event
     * @param integer $eventId
     */
    protected function deleteEvent ($eventId) {
        $model = Event::findOne($eventId);
        if ($model->delete()) {
            echo "Event $eventId deleted successfully\n";
        } else {
            echo "FAILED to delete event $eventId\n";
        }
    }
    /**
     * Create an event
     * @param array $googleEvent
     */
    protected function createEvent ($googleEvent) {
        
        //create event
        $model = new Event();
        $model->googleId = $googleEvent['googleId'];
        $model->subject = $googleEvent['subject'];
        $model->start_dt = $googleEvent['startDt'];
        $model->last_edit_dt = date('Y-m-d H:i:s');
        $model->end_dt = $googleEvent['endDt'];;
        $model->user_id = 1; //assume admin
        $model->group = $googleEvent['group'];
        $model->all_day = $googleEvent['allDay'];
        $model->repeat_interval = $googleEvent['repeat'];
        $model->repeat_days = $googleEvent['repeatDays'];
        $model->save(false);
        echo "Creating google event, $model->googleId.\n";
    }

    /**
     * Converts Google event into Sibley compatable event
     * @param object $event
     * @param array $rule
     */
    protected function formatEvent($event, $rules = []) {
        
        $start = '';
        $end = '';
        if (isset($event->start)) {
            $start = $event->start->dateTime;
        }
        if (empty($start)) {
            return;
        }

        $end = $event->start->dateTime;
        if (isset($event->end)) {
            $end = $event->end->dateTime;
        }
        $description = $event->description;
        $kind = $event->kind;
        $status = $event->status;
        $etag = $event->etag;
        $summary = $event->summary;
        $location = $event->location;

        $repeatInterval = 0;
        $allDay = 1;
        $repeatDays = '';

        printf("%s (%s - %s), Desc: %s, Loc: %s\n", $event->getSummary(), $start, $end, $description, $location);
        if (!empty($rules)) {
            print_r($rules);
            /*
                [0] => FREQ=WEEKLY
                [1] => WKST=SU
                [2] => UNTIL=20190502T045959Z
                [3] => BYDAY=WE,TH
            */
            foreach($rules as $ruleSet) {

                //1 - weekly, 2 - bi-weekly, 3 - monthly, 4 - annual, 5 - multi/week
                if ($ruleSet[0] == 'FREQ=WEEKLY') {
                    $repeatInterval = 1;
                    
                    //if (strpos($ruleSet[1], 'WKST') !== false) {
                    //}
                    $idx = count($ruleSet)-1;
                    $byDay = explode('=',$ruleSet[$idx])[1];
                    $repeatDays = $byDay;
                    $days = explode(',',$byDay);
                    if (count($days) > 1) {
                        $repeatInterval = 5;
                    }
                }
                //find end date, NOTE @TODO - This will bust end durantion timestamp!!
                $search_text = 'UNTIL';
                $untilDt = array_filter($ruleSet, function($el) use ($search_text) {
                    return ( strpos($el, $search_text) !== false ); 
                    //if ( strpos($el, $search_text) !== false ) {
                    //    $endDt = explode('=',$el)[1];
                    //    echo "Found $endDt \n";
                    //    return $endDt;
                    //}
                });
                //print_r($untilDt);
                $endDt = explode('=', $untilDt[key($untilDt)])[1]; 
                echo "Real end DT: $endDt\n";
                if (!empty($endDt)) {
                    $end = $endDt;
                }
                //fix duration timestamp
                
            }
            
        }

        //check time stamp
        if ( date("Y-m-d H:i:s", strtotime($start)) > date("Y-m-d 00:00:00", strtotime($start)) ) {
            $allDay = 0;
        }

        //create event array
        $sibEvent = [
            'subject' => $summary,
            'startDt' => date("Y-m-d H:i:s", strtotime($start)),
            'endDt'   => date("Y-m-d H:i:s", strtotime($end)),
            'lastEdit'=> date('Y-m-d H:i:s'),
            'userEdit'=> 1,
            'group'   => 'rec',
            'allDay'  => $allDay,
            'repeat'  => $repeatInterval,
            'repeatDays' => $repeatDays,
            'status'  => $status,
            'googleId'=> $event->id,
        ];
        
        return $sibEvent;
    }
    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    protected function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('City Of Sibley Calendar API Test');
        $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
        $client->setAuthConfig(Yii::getAlias('@frontend/config/google/credentials_b.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        //$client->dateDefaultTimezoneSet('America/Los_Angeles');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = Yii::getAlias('@frontend/config/google/token_b.json');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }
}