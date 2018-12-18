<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Staff;
use frontend\models\StaffSearch;
use frontend\models\StaffElected;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use frontend\models\ImageAsset;

/**
 * StaffController implements the CRUD actions for Staff model.
 */
class StaffController extends Controller
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
     * Lists all Staff models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StaffSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Staff model.
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
     * Creates a new Staff model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('create_staff')) {
            $model = new Staff();
            $elected = new StaffElected();
            $imgAssets = ImageAsset::retrieveAssets();

            if ($model->load(Yii::$app->request->post()) && $elected->load(Yii::$app->request->post())) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'The staff member was successfully created.');
                    if ($model->elected == 1) {
                        $elected->staff_id = $model->id;
                        $elected->term_start = date("Y-m-d", strtotime($elected->term_start));
                        $elected->term_end = date("Y-m-d", strtotime($elected->term_end));
                        if ($elected->save()) {
                            Yii::$app->session->setFlash('success', 'The elected staff member was successfully created.');
                        } else {
                            Yii::$app->session->setFlash('error', 'An error occured while attempting to save election information.');
                        }
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'An error occured while creating this staff memeber. No changes were saved:' . print_r(Html::error($model), true));
                }    
                

                return $this->redirect(['sibley/staff']);
            }

            return $this->render('create', [
                'model' => $model,
                'elected' => $elected,
                'imgAssets' => $imgAssets
            ]);
        } else {
            throw new ForbiddenHttpException('You do not have permission to access this page.');
        }
    }

    /**
     * Updates an existing Staff model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {       
        if (Yii::$app->user->can('update_staff')) {
            $model = $this->findModel($id);
            $elected = StaffElected::find()->where(['staff_id' => $id])->one();
            if ($elected == null) {
                $elected = new StaffElected();
            }
            $imgAssets = ImageAsset::retrieveAssets();

            if ($model->load(Yii::$app->request->post()) && $elected->load(Yii::$app->request->post())) {
                if($model->validate() && $elected->validate()){
                    if ($model->save(false)) {
                        Yii::$app->session->setFlash('success', 'The staff form was successfully updated.');
                        if ($model->elected == 1) {
                            $elected->staff_id = $model->id;
                            if (empty($elected->term_start)) {
                                Yii::$app->session->setFlash('error', 'Term start is empty.');
                                return $this->redirect(['staff/update/'.$id]);
                            }
                            $elected->term_start = date("Y-m-d", strtotime($elected->term_start));
                            $elected->term_end = date("Y-m-d", strtotime($elected->term_end));
                            if ($elected->save(false)) {
                                Yii::$app->session->setFlash('success', 'The staff form was successfully updated.');
                            } else {
                                Yii::$app->session->setFlash('error', 'An error occured while attempting to save election information.');
                            }
                        }else {
                            //delete elected data if exists
                            $elected->delete();
                        }
                    } else {
                        Yii::$app->session->setFlash('error', 'An error occured while updating the staff form. ele: '.$model->elected.'. No changes were saved:' . print_r(Html::error($model,'elected'), true) . ', ' . print_r(Html::error($elected,'term_start'), true));
                    }     
                    return $this->redirect(['sibley/staff']);
                }
                Yii::$app->session->setFlash('error', 'An error occured while setting image asset:' . print_r(Html::error($model,'image_asset'), true));
                Yii::$app->session->setFlash('error', 'An error occured while updating the staff form. ele: '.$model->elected.'. No changes were saved:' . print_r(Html::error($model,'elected'), true) . ', ' . print_r(Html::error($elected,'term_start'), true));
            }
    
            $elected->term_start = date("m/d/Y", strtotime($elected->term_start));
            $elected->term_end = date("m/d/Y", strtotime($elected->term_end));
            return $this->render('update', [
                'model' => $model,
                'elected' => $elected,
                'imgAssets' => $imgAssets
            ]);
        } else {
            throw new ForbiddenHttpException('You do not have permission to access this page.');
        }
    }

    /**
     * Deletes an existing Staff model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('delete_staff')) {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        } else {
            throw new ForbiddenHttpException('You do not have permission to access this page.');
        }    
    }

    /**
     * Finds the Staff model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Staff the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Staff::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
