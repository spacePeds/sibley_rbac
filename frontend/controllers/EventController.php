<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Event;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\bootstrap4\ActiveForm;  //use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\User;
use yii\helpers\Url;
use frontend\models\Audit;
/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends Controller
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
     * Lists all Event models.
     * @return mixed
    */
    public function actionIndex()
    {
        return $this->redirect(['/sibley/calendar']);
        /*
        $events = Event::find()->all();
        $eventArr = [];
        foreach ($events as $event) 
        {
            //https://fullcalendar.io/docs/event-object
            $e = new \yii2fullcalendar\models\Event();
            $e->id = $event->id;
            $e->title = $event->subject;
            $e->start = $event->start_dt;
            $e->end = $event->end_dt;
            $e->nonstandard = [
                'description' => $event->description
            ];
            if ($event->group == 'city') {
                $e->backgroundColor = 'green';
            }
            $eventArr[] = $e;
        }

        return $this->render('index', [
            'events' => $eventArr,
        ]);
        */
    }
 
    /**
     * Displays a single Event model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    /**
     * Displays a single Event model.
     * @param integer $id
     * @return json
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionGetAjaxEvent($id)
    {
        //find event if exists
        $event = $this->findModel($id);
        
        //define ics array
        $ics = [];
        $status = 'error';
        $message = 'No event found';

        if ($event) {
            $status = 'success';
            $message = 'Event Found';
            //duration
            if (!empty($event->end_dt)) {
                $duration = '';
                if (date('mdY', strtotime($event->start_dt)) == date('mdY', strtotime($event->end_dt))) {
                    
                    //same dates, are times different?
                    $date1 = new \DateTime($event->end_dt);
                    $date2 = new \DateTime($event->start_dt);
                    $interval = $date1->diff($date2);
                    if ($date1 > $date2) {
                        $duration = 'Duration: ' . $interval->h . ' hours, ' . $interval->i . ' minutes';
                    }
                    
                    $ics['startDt'] = date('m/d/Y h:i a', strtotime($event->start_dt));
                    $ics['endDt'] = date('m/d/Y h:i a', strtotime($event->end_dt));

                    $event->start_dt = date("M j, Y h:ia", strtotime($event->start_dt));
                    $event->notes = $duration;
                    
                } else {                   
                    $ics['startDt'] = date('m/d/Y h:i a', strtotime($event->start_dt));
                    $ics['endDt'] = date('m/d/Y h:i a', strtotime($event->end_dt));
                    
                    $event->start_dt = date("M j, Y", strtotime($event->start_dt)) . ' - ' .  date("M j, Y", strtotime($event->end_dt));
                    
                }  
            } else {
                //end date is unspecified
                $ics['startDt'] = date('m/d/Y h:i a', strtotime($event->start_dt));
                $ics['endDt'] = date('m/d/Y h:i a', strtotime($event->start_dt));
            }
            
            if($event->all_day == 1) {
                //define JS vals first before overwriting start_dt param
                $ics['startDt'] = date('m/d/Y', strtotime($event->start_dt));
                $ics['endDt'] = date('m/d/Y', strtotime($event->end_dt));

                //$event->start_dt = date("F j, Y", strtotime($event->start_dt)) . ' (All Day)';
                $event->notes .= ' (All Day)';
            }

            //apply user-friendly group label
            $groups = Yii::$app->params['eventGroups'];
            $event->group = $groups[$event->group];
            $ics['group'] = $event->group;
            
            //find document if exists
            $pdfFileInfo = $event->getAttachment($id);
            if (!empty($pdfFileInfo)) {
                if (file_exists(Url::to('@frontend/web') . $pdfFileInfo['path'] . $pdfFileInfo['name'])) {
                    $event->pdfFile = Yii::getAlias('@web') . $pdfFileInfo['path'] . $pdfFileInfo['name'];
                    $event->pdfFileName = $pdfFileInfo['name'] . ' ('.$this->formatBytes($pdfFileInfo['size']).')';
                    $ics['pdf'] = Url::to('@web', true) . $pdfFileInfo['path'] . $pdfFileInfo['name'];
                }
            }

            $ics['notes'] = $event->notes;
            $ics['location'] = $event->location;
            $ics['description'] = strip_tags($event->description);
            $ics['subject'] = $event->subject;
            $ics['rrule'] = '';
            
            switch($event->repeat_interval) {
                case 1:
                    //weekly
                    //$jsVars['rrule'] = "{'RRULE':'FREQ=WEEKLY;UNTIL=".$jsVars['endDt']."'}";
                    $ics['rrule'] = "RRULE:FREQUENCY=WEEKLY;UNTIL=".$ics['endDt'];
                    break;
                case 2:
                    //bi-weekly
                    $ics['rrule'] = "RRULE:FREQ=WEEKLY;INTERVAL=2,UNTIL=".$ics['endDt'];
                    break;
                case 3:
                    //bi-weekly
                    $ics['rrule'] = "RRULE:FREQ=MONTHLY,UNTIL=".$ics['endDt'];
                    break;
                case 4:
                    //annually
                    $ics['rrule'] = "RRULE:FREQ=YEARLY,UNTIL=".$ics['endDt'];
                    break;
                default:
                $ics['rrule'] = '';
            }

        }

        $result = [
            'status' => $status,
            'message' => $message,
            'payload' => [
                'event' => ArrayHelper::toArray($event),
                'ics' => $ics
            ]
        ];
        echo Json::encode($result);

    }
    /**
     * Displays a single Event model in a modal.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionViewModal($id)
    // {
    //     //find event if exists
    //     $event = $this->findModel($id);
        
    //     //define ics array
    //     $jsVars = [];

    //     //duration
    //     if (!empty($event->end_dt)) {
    //         $duration = '';
    //         if (date('mdY', strtotime($event->start_dt)) == date('mdY', strtotime($event->start_dt))) {
    //             //same dates, are times different?
    //             $date1 = new \DateTime($event->end_dt);
    //             $date2 = new \DateTime($event->start_dt);
    //             $interval = $date1->diff($date2);
    //             if ($date1 > $date2) {
    //                 $duration = 'Duration: ' . $interval->h . ' hours, ' . $interval->i . ' minutes';
    //             }
                
    //             $jsVars['startDt'] = date('m/d/Y h:i a', strtotime($event->start_dt));
    //             $jsVars['endDt'] = date('m/d/Y h:i a', strtotime($event->end_dt));

    //             $event->start_dt = date("M j, Y h:ia", strtotime($event->start_dt));
    //             $event->notes = $duration;
                
    //         } else {
    //             $jsVars['startDt'] = date('m/d/Y h:i a', strtotime($event->start_dt));
    //             $jsVars['endDt'] = date('m/d/Y h:i a', strtotime($event->start_dt));
                
    //             $event->start_dt = date("M j, Y", strtotime($event->start_dt)) . ' - ' .  date("M j, Y", strtotime($event->end_dt));
                
    //         }  
    //     }
        
    //     if($event->all_day == 1) {
    //         //define JS vals first before overwriting start_dt param
    //         $jsVars['startDt'] = date('m/d/Y', strtotime($event->start_dt));
    //         $jsVars['endDt'] = date('m/d/Y', strtotime($event->end_dt));

    //         $event->start_dt = date("F j, Y", strtotime($event->start_dt)) . ' (All Day)';
    //     }

    //     //apply user-friendly group label
    //     $groups = Yii::$app->params['eventGroups'];
    //     $event->group = $groups[$event->group];
        
    //     //find document if exists
    //     $pdfFileInfo = $event->getAttachment($id);
    //     if (!empty($pdfFileInfo)) {
    //         if (file_exists(Url::to('@frontend/web') . $pdfFileInfo['path'] . $pdfFileInfo['name'])) {
    //             $event->pdfFile = Yii::getAlias('@web') . $pdfFileInfo['path'] . $pdfFileInfo['name'];
    //             $event->pdfFileName = $pdfFileInfo['name'] . ' ('.$this->formatBytes($pdfFileInfo['size']).')';
    //             $jsVars['pdf'] = Url::to('@web', true) . $pdfFileInfo['path'] . $pdfFileInfo['name'];
    //         }
    //     }

    //     $jsVars['notes'] = $event->notes;
    //     $jsVars['subject'] = $event->subject;
    //     $jsVars['location'] = $event->location;
    //     $jsVars['description'] = $event->description;
    //     $jsVars['rrule'] = '';
        
    //     switch($event->repeat_interval) {
    //         case 1:
    //             //weekly
    //             //$jsVars['rrule'] = "{'RRULE':'FREQ=WEEKLY;UNTIL=".$jsVars['endDt']."'}";
    //             $jsVars['rrule'] = "{rrule: 'RRULE:FREQ=WEEKLY;UNTIL=".$jsVars['endDt']."'}";
    //             break;
    //         case 2:
    //             //bi-weekly
    //             $jsVars['rrule'] = "{rrule: 'RRULE:FREQ=WEEKLY;INTERVAL=2,UNTIL=".$jsVars['endDt']."'}";
    //             break;
    //         case 3:
    //             //bi-weekly
    //             $jsVars['rrule'] = "{rrule: 'RRULE:RRULE:FREQ=MONTHLY,UNTIL=".$jsVars['endDt']."'}";
    //             break;
    //         case 4:
    //             //annually
    //             $jsVars['rrule'] = "{rrule: 'RRULE:RRULE:FREQ=YEARLY,UNTIL=".$jsVars['endDt']."'}";
    //             break;
    //         default:
    //         $jsVars['rrule'] = '';
    //     }
    //     return $this->renderAjax('viewModal', [
    //         'model' => $event,
    //         'pdf' => $pdfFileInfo,
    //         'jsVar' => $jsVars
    //     ]);
    // }

    /**
     * Creates a new Event model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($date)
    {
        $model = new Event();
        $model->start_dt = $date;
        $user_id = User::findByUsername(Yii::$app->user->identity->username)->getId();

        if ($model->load(Yii::$app->request->post())) {
            $model->last_edit_dt = date('Y-m-d H:i:s');
            
            $model->start_dt = date("Y-m-d H:i:s", strtotime($model->start_dt));
            if($model->all_day == 1) {
                $model->start_dt = date("Y-m-d", strtotime($model->start_dt));
            }
            if (!empty($model->end_dt)) {
                $model->end_dt = date("Y-m-d H:i:s", strtotime($model->end_dt));
            } else {
                $model->end_dt = $model->start_dt;
            }
            
            $model->user_id = $user_id;
            $model->pdfFile = UploadedFile::getInstance($model, 'pdfFile');
            if ($model->validate()) {
                if ($model->save(false)) {
                    //save event before uploading attachment so we have a ID to link to
                    $audit = new Audit();
                    $audit->table = 'event';
                    $audit->record_id = Yii::$app->db->getLastInsertID();
                    $audit->field = 'subject';
                    $audit->new_value = $model->subject;
                    $audit->update_user = $user->id;
                    $audit->save(false);

                    if ($model->pdfFile) {
                        if ($model->upload($model->id)) { 
                            Yii::$app->session->setFlash('success', "Event created successfully with attachment: " . $model->pdfFile->name);
                            return $this->redirect(['sibley/calendar']);
                        } else {
                            //Yii::$app->session->setFlash('error', "Upload failed." . Yii::$app->params['media']);
                            return $this->redirect(['sibley/calendar']);
                        }
                    }
                    //no error, no attachment
                    Yii::$app->session->setFlash('success', "Event created successfully.");
                    return $this->redirect(['sibley/calendar']);
                } else {
                    Yii::$app->session->setFlash('error', "Event failed to save.");
                    return $this->redirect(['sibley/calendar']);
                }
                
            } else {
                Yii::$app->session->setFlash('error', "Validation failed.");
                return $this->redirect(['sibley/calendar']);
            }

            
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
                'group'  => $this->getGroup(),
                'repition' => Yii::$app->params['eventRepition']
            ]);
        }

        
    }

    /**
     * Updates an existing Event model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //$user_id = User::findByUsername(Yii::$app->user->identity->username)->getId();

        //make sure only owner or site admin can edit
        $user_id = Yii::$app->user->identity->id;
        if ($user_id != $model->user_id && $user_id != 1) {
            Yii::$app->session->setFlash('error', "It does not appear you are the owner of this event. Edit request rejected.");
            return $this->goBack(Yii::$app->request->referrer);
        }

        //find document if exists
        $pdfFileInfo = $model->getAttachment($id);
        

        if ($model->load(Yii::$app->request->post())) {
            $model->last_edit_dt = date('Y-m-d H:i:s');
            $model->user_id = $user_id;
            
            $model->start_dt = date("Y-m-d H:i:s", strtotime($model->start_dt));
            if($model->all_day == 1) {
                $model->start_dt = date("Y-m-d", strtotime($model->start_dt));
            }
            if (!empty($model->end_dt)) {
                $model->end_dt = date("Y-m-d H:i:s", strtotime($model->end_dt));
            } else {
                $model->end_dt = $model->start_dt;
            }

            $model->pdfFile = UploadedFile::getInstance($model, 'pdfFile');
            if ($model->validate()) {
                if ($model->save(false)) {
                    //save event before uploading attachment so we have a ID to link to
                    if ($model->pdfFile) {
                        if ($model->upload($model->id)) { 
                            Yii::$app->session->setFlash('success', "Event updated successfully with attachment: " . $model->pdfFile->name);
                            return $this->redirect(['sibley/calendar']);
                        } else {
                            Yii::$app->session->setFlash('error', "Upload failed.");
                            return $this->redirect(['sibley/calendar']);
                        }
                    }
                    //no error, no attachment
                    Yii::$app->session->setFlash('success', "Event updated successfully.");
                    return $this->redirect(['sibley/calendar']);
                } else {
                    Yii::$app->session->setFlash('error', "Event failed to update.");
                    return $this->redirect(['sibley/calendar']);
                }
                
            } else {
                Yii::$app->session->setFlash('error', "Validation failed.");
                return $this->redirect(['sibley/calendar']);
            }
        } else {
            if (!empty($pdfFileInfo)) {
                $model->pdfFile = '<a href="'. Yii::getAlias('@web') .'/'. $pdfFileInfo['path'] . $pdfFileInfo['name'].'">' . $pdfFileInfo['name'] . ' ('.$pdfFileInfo['size'].')</a>';
            }
            $model->start_dt = date("m/d/Y H:i A", strtotime($model->start_dt));
            $model->end_dt = date("m/d/Y H:i A", strtotime($model->end_dt));
            
            return $this->renderAjax('update', [
                'model' => $model,
                'group'  => $this->getGroup(),
                'repition' => Yii::$app->params['eventRepition']
            ]);
        }    
    }
    /**
     * Ajax Validation on Events form
     * 
     */
    public function actionValidation($id=0)
    {
        $model = new Event();
        if ($id > 0)
        {
            $model = $this->findModel($id);
        }
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            Yii::$app->response->format = 'json';
            return ActiveForm::validate($model);
        }
    }

    /**
     * Updates an existing Event model.
     * Ajax passthrough
     * @param integer $id
     * @param string $startDate
     * @param string $endDate
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate_ajax() 
    {
        $posted = Yii::$app->request->post();
        $id = $posted['id'];
        $startDate = $posted['startDate'];
        $endDate = $posted['endDate'];

        $model = $this->findModel($id);

        $model->start_dt = $startDate;
        $model->end_dt = $startDate;
        if (!empty($endDate)) {
            $model->end_dt = $endDate;
        }
        $status = 'error';
        $message = 'Failed to update event: ' . $id;
        
        //make sure only owner or site admin can edit
        $user_id = Yii::$app->user->identity->id;
        if ($user_id != $model->user_id && $user_id != 1) {
            $status = 'error';
            $message = "It does not appear you are the owner of this event. Edit request rejected.";
        } elseif ($model->save()) {
            $status = 'success';
            $message = '';
        }

        $result = [
            'status' => $status,
            'message' => $message,
            'posted' => [
                'id' => $id,
                'start' => $startDate,
                'end'   => $endDate
            ]
        ];
        echo Json::encode($result);
    }

    /**
     * Deletes an existing Event model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        //make sure only owner or site admin can delete
        $user_id = Yii::$app->user->identity->id;
        if ($user_id != $model->user_id && $user_id != 1) {
            Yii::$app->session->setFlash('error', "It does not appear you are the owner of this event. Delete request rejected.");
            return $this->goBack(Yii::$app->request->referrer);
        }

        //find document if exists
        $pdfFileInfo = $model->getAttachment($id);
        if (!empty($pdfFileInfo) && file_exists(Url::to('@frontend/web') . $pdfFileInfo['path'] . $pdfFileInfo['name'])) {         
            if (unlink(Url::to('@frontend/web') . $pdfFileInfo['path'] . $pdfFileInfo['name'])) {
                $model->delete();
                Yii::$app->session->setFlash('success', "Successfully deleted event and and attached document.");
            } else {
                Yii::$app->session->setFlash('error', "Unable to delete attached document: " . $pdfFileInfo['name']. ".");
            }
        } else {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', "Successfully deleted event $id.");
        }
        

        return $this->redirect(['sibley/calendar']);
    }

    /**
     * Finds the Event model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Event the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /**
     * Limit event group to appropiate options for logged in user
     * @return array;
     */
    protected function getGroup() {
        //what role does this user play?
        //$user_id = Yii::$app->user->identity->id;
        $group = [];
        $user_id = User::findByUsername(Yii::$app->user->identity->username)->getId();            
        $role = \Yii::$app->authManager->getRolesByUser($user_id);
        if ($role['superAdmin']) {
            $group = Yii::$app->params['eventGroups'];
        } else if ($role['cityAdmin']) {
            $group = ['city'];
        } else if ($role['chamberAdmin']) {
            $group = ['chamber'];
        } else if ($role['recAdmin']) {
            $group = ['rec'];
        }
        return $group;
    }
    /**
     * Format a raw file zise return from PHP
     * @param integer $bytes
     * @param integer $precision
     */
    protected function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'K', 'M', 'G', 'T');   
    
        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }
}
