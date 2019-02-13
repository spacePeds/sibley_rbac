<?php

namespace frontend\controllers;

use Yii;
use frontend\models\HeaderImage;
use frontend\models\HeaderImageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Event;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * HeaderImageController implements the CRUD actions for HeaderImage model.
 */
class HeaderImageController extends Controller
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
     * Lists all HeaderImage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HeaderImageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HeaderImage model.
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
     * Creates a new HeaderImage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HeaderImage();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = Yii::$app->user->identity->id;
            $model->last_edit = date('Y-m-d H:i:s');

            $model->uploadedImage = UploadedFile::getInstance($model, 'uploadedImage');
            $imgPath = 'img/assets/';
            $documentName = 'headerImg_' . time().rand(100,999) . '.' . $model->uploadedImage->extension;
            $model->image_path = '/'. $imgPath . $documentName;
            if ($model->save()) {
                $imgId = Yii::$app->db->getLastInsertID();
                if ($model->upload($imgPath . $documentName)) {
                    // file is uploaded successfully
                    $model->ajaxResult['message'] .= ' Record saved succcessfully.';               
                    $model->ajaxResult['recordId'] = $imgId;               
                }                  
                $data = ArrayHelper::toArray($model);
                $model->ajaxResult['form'] = $data;
            } else {
                $model->ajaxResult = ['status' => 'error','message' => 'Failed to save record.'];
            }
                  
            return $this->asJson($model->ajaxResult);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing HeaderImage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing HeaderImage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        $data = Yii::$app->request->post();
        $model = $this->findModel($data['id']);
        $sysPath = Url::to('@webroot') . $model->image_path;
        
        if (!$model->delete()) {
            $model->ajaxResult = ['status' => 'error','message' => 'Failed to delete record. ' . print_r($data,true)];
        } else {
            $model->ajaxResult = [
                'status' => 'success',
                'message' => 'Record Deleted.',
                'imgPath' => $sysPath
            ];
            if (file_exists($sysPath)) {
                unlink($sysPath);
            } 
        }
        return $this->asJson($model->ajaxResult);
    }

    /**
     * Ajax Validation on Header Image form
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
     * Finds the HeaderImage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HeaderImage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HeaderImage::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
