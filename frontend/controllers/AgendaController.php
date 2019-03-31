<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Agenda;
use frontend\models\AgendaMinutes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\bootstrap4\ActiveForm;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use frontend\models\Audit;

/**
 * AgendaController implements the CRUD actions for Agenda model.
 */
class AgendaController extends Controller
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
     * Refresh nav-menu to current year.
     * @param string $y
     * @return mixed
     */
    public function actionGenerateMenu($yr)
    {
        $startDt = $yr.'-01-01';
        $endDt = $yr.'-12-31';
        $model = Agenda::find()->select(['id','type','date'])->where(
            ['between', 'date', $startDt, $endDt ])->orderBy('date')->all();
        //group results by month
        $meetings = [];
        
        foreach($model as $meeting) {
            $month = date("F", strtotime($meeting->date));
            $meetingLabel = ucfirst($meeting->type) . ' Meeting: ' . date("D M jS", strtotime($meeting->date));
            $meetings[$month][] = [
                'label' => $meetingLabel,
                'id' => $meeting->id
            ];
               
        }
        return $this->renderAjax('menu', [
            'meetings' => $meetings
        ]);
    }

    /**
     * Load the appropriate agenda and minutes based on provided agenda id.
     * @param integer $id
     * @return mixed
     */
    public function actionGetAgenda($id)
    {
        $model = Agenda::find()
            ->select([
                'agenda.id AS aId',
                'agenda.type',
                'agenda.date',
                'agenda.body AS aBody',
                'agenda.create_dt AS aCreateDt',
                'agenda.created_by AS aCreatedBy',
                'user.first_name As uFname',
                'user.last_name As uLname',
                'agenda_minutes.id AS mId',
                'agenda_minutes.attend',
                'agenda_minutes.absent',
                'agenda_minutes.body AS mBody',
                'agenda_minutes.video AS mVideo',
                'agenda_minutes.create_dt AS mCreateDt'
            ])
            ->leftJoin('agenda_minutes', '`agenda_minutes`.`agenda_id` = `agenda`.`id`')
            ->leftJoin('user', '`user`.`id` = `agenda`.`created_by`')
            ->where(['agenda.id'=>$id])->asArray()->one();
            //$model = AgendaMinutes::find()->select([
        //        'agenda_minutes.*', 'agenda.*'
        //    ])->
        //    joinWith('agenda',true,'RIGHT JOIN')->where(['agenda.id'=>$id])->one();
        //foreach($model as $agenda) {
        //    $agenda['createDt'] = date('m/d/Y',$agenda->create_dt);
        //}
        //['agenda.id','agenda.type','agenda.date','agenda.body AS bananna','agenda.create_dt','agenda_minutes.id','agenda_minutes.attend','agenda_minutes.absent','agenda_minutes.body AS cheeseburger','agenda_minutes.create_dt']

        return $this->renderAjax('agenda', [
            'agenda' => $model
        ]);
    }
    /**
     * Lists all Link models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('/sibley/council');
    }
    /**
     * Displays a single Agenda model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Agenda model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('create_agenda')) {
            $model = new Agenda();
            $model->scenario ='create';   //helps with unique meeting on date validation

            if ($model->load(Yii::$app->request->post())) {

                $model->create_dt = date('Y-m-d');
                $model->created_by = Yii::$app->user->identity->id;
                $dateNote = date("l F jS", strtotime($model->date));
                $model->date = date("Y-m-d", strtotime($model->date));
                
                if ($model->save()) {
                    $audit = new Audit();
                    $audit->table = 'agenda';
                    $audit->record_id = $model->id;
                    $audit->field = 'Create';
                    $audit->update_user = $model->created_by;
                    $audit->save(false);
                    Yii::$app->session->setFlash('success', "Agenda successfully set for " . $dateNote . " meeting.");
                } else {
                    Yii::$app->session->setFlash('error', "Failed to set meeting agenda. Error: " . Html::error($model,'date'));
                }
                return $this->redirect(['sibley/council', 'id' => $model->id]);
            }

            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        } else {
            throw new ForbiddenHttpException('You do not have permission to perform this action.');
        }    
    }

    /**
     * Updates an existing Agenda model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->can('update_agenda')) {

            $model = $this->findModel($id);

            //make sure only owner or site admin can update
            $user_id = Yii::$app->user->identity->id;
            if ($user_id != $model->created_by && $user_id != 1) {
                Yii::$app->session->setFlash('error', "It does not appear you created this agenda. Update request rejected.");
                return $this->goBack(Yii::$app->request->referrer);
            }

            if ($model->load(Yii::$app->request->post())) {
                $dateNote = date("l F jS", strtotime($model->date));
                $model->date = date("Y-m-d", strtotime($model->date));
                
                if ($model->save()) {
                    $audit = new Audit();
                    $audit->table = 'agenda';
                    $audit->record_id = $model->id;
                    $audit->field = 'Update';
                    $audit->update_user = Yii::$app->user->identity->id;
                    $audit->save(false);
                    Yii::$app->session->setFlash('success', "Agenda successfully updated for $dateNote meeting.");
                } else {
                    Yii::$app->session->setFlash('error', "Failed to update meeting agenda. Error: " . Html::error($model,'date'));
                }
                return $this->redirect(['sibley/council', 'id' => $model->id]);
            }

            $model->date = date("m/d/Y", strtotime($model->date));
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        } else {
            throw new ForbiddenHttpException('You do not have permission to perform this action.');
        }
    }

    /**
     * Deletes an existing Agenda model.
     * If deletion is successful, the browser will be redirected to the 'meetings' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('delete_agenda')) {

            //make sure only owner or site admin can update
            $user_id = Yii::$app->user->identity->id;
            $model = $this->findModel($id);
            if ($user_id != $model->created_by && $user_id != 1) {
                Yii::$app->session->setFlash('error', "It does not appear you created this agenda. Delete request rejected.");
                return $this->goBack(Yii::$app->request->referrer);
            }
        
            if ($this->findModel($id)->delete()) {
                $audit = new Audit();
                $audit->table = 'agenda';
                $audit->record_id = $id;
                $audit->field = 'Delete';
                $audit->update_user = Yii::$app->user->identity->id;
                $audit->save(false);
                Yii::$app->session->setFlash('success', "Agenda successfully deleted. Any associated minutes have also been deleted.");
            } else {
                Yii::$app->session->setFlash('error', "An error occured while deleting this agenda");
            }

            return $this->redirect(['sibley/council']);

        } else {
            throw new ForbiddenHttpException('You do not have permission to perform this action.');
        }
    }

    /**
     * Ajax Validation on Events form
     * 
     */
    public function actionValidation($id=0)
    {
        $model = new Agenda();
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
     * Finds the Agenda model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Agenda the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Agenda::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
