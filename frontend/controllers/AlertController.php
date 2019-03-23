<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Alert;
use common\models\User;
use frontend\models\AlertSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use frontend\models\Audit;

/**
 * AlertController implements the CRUD actions for Alert model.
 */
class AlertController extends Controller
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
     * Lists all Alert models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AlertSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Alert model.
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
     * Creates a new Alert model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('create_alert')) {
            $model = new Alert();          

            if ($model->load(Yii::$app->request->post())) {
                $user_id = Yii::$app->user->identity->id;
                $model->created_by = $user_id;
                $model->created_dt = date('Y-m-d H:i:s');
                if ($model->validate()) {
                    //format dates for DB
                    $model->start_dt = date("Y-m-d", strtotime($model->start_dt));
                    $model->end_dt = date("Y-m-d 23:59:59", strtotime($model->end_dt));
                    if ($model->save(false)) {
                        $audit = new Audit();
                        $audit->table = 'alert';
                        $audit->record_id = $model->id;
                        $audit->field = 'Create';
                        $audit->update_user = Yii::$app->user->identity->id;
                        $audit->save(false);
                        Yii::$app->session->setFlash('success', 'Site-wide notification successfully created.');
                    } else {
                        Yii::$app->session->setFlash('error', 'An error occured while creating the Site-wide notification.' . print_r($model, true));
                    }
                    return $this->goBack(Yii::$app->request->referrer);
                }
            }

            return $this->renderAjax('create', [
                'model' => $model,
                'group'  => $this->getGroup()
            ]);
        } else {
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Updates an existing Alert model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->can('update_alert')) {

            $model = $this->findModel($id);

            //make sure only owner or site admin can update
            $user_id = Yii::$app->user->identity->id;
            if ($user_id != $model->created_by && $user_id != 1) {
                Yii::$app->session->setFlash('error', "It does not appear you are the owner of this site-wide alert. Update request rejected.");
                //return $this->goBack(Yii::$app->request->referrer);
            }

            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $model->start_dt = date("Y-m-d", strtotime($model->start_dt));
                    $model->end_dt = date("Y-m-d 23:59:59", strtotime($model->end_dt));
                    if ($model->save(false)) {
                        $audit = new Audit();
                        $audit->table = 'alert';
                        $audit->record_id = $model->id;
                        $audit->field = 'Create';
                        $audit->update_user = Yii::$app->user->identity->id;
                        $audit->save(false);
                        Yii::$app->session->setFlash('success', 'Notification successfully updated.');
                    } else {
                        Yii::$app->session->setFlash('error', 'An error occured while modifying the notification.');
                    }
                    return $this->goBack(Yii::$app->request->referrer);
                }
            }

            //format the dates for Merika
            $model->start_dt = date("m/d/Y", strtotime($model->start_dt));
            $model->end_dt = date("m/d/Y", strtotime($model->end_dt));
            return $this->renderAjax('update', [
                'model' => $model,
                'group'  => $this->getGroup()
            ]);

        } else {
            throw new ForbiddenHttpException('You do not have permission to access this page.');
        }
    }

    /**
     * Deletes an existing Alert model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (($model = Alert::findOne($id)) !== null) {

            //make sure only owner or site admin can delete
            $user_id = Yii::$app->user->identity->id;
            if ($user_id != $model->created_by && $user_id != 1) {
                Yii::$app->session->setFlash('error', "It does not appear you are the owner of this link. Delete request rejected.");
                return $this->goBack(Yii::$app->request->referrer);
            }

            $audit = new Audit();
            $audit->table = 'alert';
            $audit->record_id = $model->id;
            $audit->field = 'Delete';
            $audit->update_user = $user_id;
            $audit->save(false);

            //delete the alert
            $model->delete();
        }

        
        return $this->goBack(Yii::$app->request->referrer);
    }

    /**
     * Finds the Alert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Alert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Alert::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    /**
     * Limit alert group to appropiate options for logged in user
     * @return array;
     */
    protected function getGroup() {
        //what role does this user play?
        //$user_id = Yii::$app->user->identity->id;
        $group = [];
        $user_id = User::findByUsername(Yii::$app->user->identity->username)->getId();            
        $role = \Yii::$app->authManager->getRolesByUser($user_id);
        if (isset($role['superAdmin'])) {
            $group = [
                'city' => 'City',
                'chamber' => 'Chamber',
                'rec' => 'Recreation Department'];
        } else if (isset($role['cityAdmin'])) {
            $group = ['city'];
        } else if (isset($role['chamberAdmin'])) {
            $group = ['chamber'];
        } else if (isset($role['recAdmin'])) {
            $group = ['rec'];
        }
        return $group;
    }
}
