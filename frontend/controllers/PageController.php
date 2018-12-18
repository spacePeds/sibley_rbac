<?php

namespace frontend\controllers;

use Yii;
use frontend\models\PageWithCategories;
use frontend\models\Category;
use frontend\models\Page;
use frontend\models\PageSearch;
use frontend\models\ImageAsset;
use frontend\models\UploadForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\User;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\db\Query;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
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
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Page model.
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
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $user_id = User::findByUsername(Yii::$app->user->identity->username)->getId();
        $model = new PageWithCategories();

        if ($model->load(Yii::$app->request->post())) {
            $model->last_edit_dt = date('Y-m-d H:i:s');
            $model->user_id = $user_id;
            if ($model->save()) {
                $model->saveCategories();
                Yii::$app->session->setFlash('success', 'Insert successful.');
                return $this->redirect([Yii::$app->request->post('route')]);
            }
        }

/*
        if ($model->load(Yii::$app->request->post())) {
            $model->last_edit_dt = date('Y-m-d H:i:s');
            $model->user_id = 1;    
            $valid = $model->validate();
            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($model->save()) {
                        $model->saveCategories();
                        Yii::$app->session->setFlash('success', 'Insert successful.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    Yii::$app->session->setFlash('warning', 'Insert failed.' .print_r($e,false));
                    $transaction->rollBack();
                    return $this->redirect(['']);
                }
            }
        }
*/
        return $this->render('create', [
            'model' => $model,
            'role' => \Yii::$app->authManager->getRolesByUser($user_id),
            'categories' => Category::getAvailableCategories(),
        ]);
    }

    /**
     * View / Update Generic assets.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionMultiple()
    {
        $model = new UploadForm();
        $assets = $this->retrieveImgAssets();
        $user_id = User::findByUsername(Yii::$app->user->identity->username)->getId();

        if (Yii::$app->request->isPost) {
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            
            if (!file_exists(Url::to('@frontend/web/img/assets/'))) {
                mkdir(Url::to('@frontend/web/img/assets/'),0777,true);
            }
            $path = Url::to('/img/assets/');

            if ($model->upload($path)) {
                //file uploaded successfully
                Yii::$app->session->setFlash('success', 'Successfully Uploaded image(s).');
                return $this->redirect([Yii::$app->request->post('route')]);
            } else {             
                return $this->redirect([Yii::$app->request->post('route')]);
            }
        } 
        else
        {
            return $this->render('multiple',[
                'upload' => $model,
                'role' => \Yii::$app->authManager->getRolesByUser($user_id),
                'assets' => $assets
            ]);
        }
        
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user_id = User::findByUsername(Yii::$app->user->identity->username)->getId();
        
        //$model = $this->findModel($id);
        $model = PageWithCategories::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->last_edit_dt = date('Y-m-d H:i:s');
            $model->user_id = $user_id;    //($model->getUser()) ? $model->getUser()->id :
            if ($model->save()) {
                $model->saveCategories();
                Yii::$app->session->setFlash('success', 'Update of '.Yii::$app->request->post('title').' successful.');
                return $this->redirect([Yii::$app->request->post('route')]);
            }
            
        }
        
        
        $model->loadCategories();
        return $this->render('update', [
            'model' => $model,
            'categories' => Category::getAvailableCategories(),
            'role' => \Yii::$app->authManager->getRolesByUser($user_id)
        ]);
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Page deleted successfully.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Image asset and model record.
     * If deletion is successful, the browser will be redirected back to asset page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete2($id)
    {
        if (($model = ImageAsset::findOne($id)) !== null) {
            $imgName = $model->name;
            if ($model->delete()) {
                if (file_exists(Url::to('@frontend/web/img/assets/'.$imgName))) {
                    if (!unlink(Url::to('@frontend/web/img/assets/'.$imgName))) {
                        Yii::$app->session->setFlash('warning', 'Unable to delete image: '. Url::to('@frontend/web/img/assets/') . $imgName.'. Please conteact your website administrator to correct this.');                        
                    }                   
                }
                Yii::$app->session->setFlash('success', 'Image '.$imgName .' deleted successfully.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to delete record for image '.$imgName .'.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Unable to locate record associated with image. Please conteact your website administrator to correct this.');
        }
        return $this->redirect(['multiple']);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    /**
     * Search Asset directory for all image assets
     * Return a list of found images and some details about images
     * @return array
     */
    protected function retrieveImgAssets() 
    {
        
        $assets = (new Query())
            ->select(['id','path','type','size','name','DATE_FORMAT(created_dt,"%m/%d/%Y") as upldDt'])
            ->from('image_asset')
            ->all();
        if (count($assets) < 1) {
            $assets = [
                0 => [
                    'id' => 0,
                    'name' => 'N/A',
                    'path' => Url::to('@web/img/assets/') . 'placeholder-image.jpg',
                    'size' => '',
                    'type' => '',
                    'upldDt' => ''
                ]
            ];
        }

        return $assets;
    }
}
