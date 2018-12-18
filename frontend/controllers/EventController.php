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
use common\models\User;
use yii\helpers\Url;

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
    
    public function actionIndex()
    {
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
    }
 */
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
     * Displays a single Event model in a modal.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewModal($id)
    {
        //find event if exists
        $event = $this->findModel($id);

        //duration
        if (!empty($event->end_dt)) {
            $duration = '';
            if (date('mdY', strtotime($event->start_dt)) == date('mdY', strtotime($event->start_dt))) {
                //same dates, are times different?
                $date1 = new \DateTime($event->end_dt);
                $date2 = new \DateTime($event->start_dt);
                $interval = $date1->diff($date2);
                if ($date1 > $date2) {
                    $duration = 'Duration: ' . $interval->h . ' hours, ' . $interval->i . ' minutes';
                }
                
                $event->start_dt = date("M j, Y h:ia", strtotime($event->start_dt));
                $event->notes = $duration;
            } else {
                $event->start_dt = date("M j, Y", strtotime($event->start_dt)) . ' - ' .  date("M j, Y", strtotime($event->end_dt));
            }  
        }
        
        if($event->all_day == 1) {
            $event->start_dt = date("F j, Y", strtotime($event->start_dt)) . '(All Day)';
        }

        ////apply user-friendly group label
        $groups = Yii::$app->params['eventGroups'];
        $event->group = $groups[$event->group];
        
        //find document if exists
        $pdfFileInfo = $event->getAttachment($id);
        if (!empty($pdfFileInfo)) {
            if (file_exists(Url::to('@frontend/web') . $pdfFileInfo['path'] . $pdfFileInfo['name'])) {
                $event->pdfFile = Yii::getAlias('@web') . $pdfFileInfo['path'] . $pdfFileInfo['name'];
                $event->pdfFileName = $pdfFileInfo['name'] . ' ('.$this->formatBytes($pdfFileInfo['size']).')';
            }
        }

        return $this->renderAjax('viewModal', [
            'model' => $event,
            'pdf' => $pdfFileInfo
        ]);
    }

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
        $user_id = User::findByUsername(Yii::$app->user->identity->username)->getId();

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
        if ($model->save()) {
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
