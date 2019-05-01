<?php
// commands/GoogleController.php DEMO
namespace console\controllers;

use Yii;
use yii\console\Controller;
use Google_Client;
use Google_Service_Calendar;
use frontend\models\Event;


class GoogleController extends Controller
{
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
            $existingEvents = Event::find()->where(['>=', 'start_dt', $today])->andWhere(['<=','start_dt', $oneYrFut])->asArray()->all();
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
        echo "Load Rec Events\n";
        $client = $this->getClient();
        $service = new Google_Service_Calendar($client);

        $calendarId = 'sibley.rec@gmail.com';
        $optParams = array(
        'maxResults' => 50,
        //'orderBy' => 'startTime',
        //'singleEvents' => true,       //breakout repeating events
        //'timeMin' => date('c'),       //ISO dt (2004-02-12T15:19:21+00:00)
        );
        $results = $service->events->listEvents($calendarId, $optParams);
        //$events = $results->getItems();


        while(true) {
            foreach ($results->getItems() as $event) {
                echo $event->getSummary();
            }
            $pageToken = $results->getNextPageToken();
            if ($pageToken) {
                $optParams = array('pageToken' => $pageToken);
                $events = $service->events->listEvents($calendarId, $optParams);
                foreach ($events->getItems() as $event) {
                    echo $event->getSummary();
                }
            } else {
                break;
            }
        }
        /*
          //var_dump($events);
        foreach ($events as $event) {
            $recurrence = [];
            $start = $event->start->dateTime;
            $end = $event->end->dateTime;
            $description = $event->description;
            $kind = $event->kind;
            $status = $event->status;
            $etag = $event->etag;
            $summary = $event->summary;
            $location = $event->location;
            if (!empty($event->recurrence)) {
                $recurrence[] = $event->recurrence;
            }
            
            //foreach ($event->recurrence as $rrule){
            //    $recurrence[] = $rrule;
            //}
            if (empty($start)) {
                $start = $event->start->date;
            }
            printf("%s (%s - %s): %s\n", $event->getSummary(), $start, $end, $description);
            printf("Status: %s, %s - %s\n", $status, $kind, $location);
            print_r($recurrence);
            //if (!empty($recurrence)) {
            //    echo implode(',',$recurrence) . "\n";
            //} else {
            //    echo $recurrence . "\n";
            //}
            
            //echo var_dump($event->recurrence);
            
        }
*/



    }
    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    public function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('City Of Sibley Calendar API Test');
        $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
        $client->setAuthConfig(Yii::getAlias('@frontend/config/credentials_b.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        //$client->dateDefaultTimezoneSet('America/Los_Angeles');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = Yii::getAlias('@frontend/config/token_b.json');
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