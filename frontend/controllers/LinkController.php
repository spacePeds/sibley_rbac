<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Link;
use frontend\models\LinkSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * LinkController implements the CRUD actions for Link model.
 */
class LinkController extends Controller
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
     * Lists all Link models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LinkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Link model.
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
     * Creates a new Link model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('create_link')) {
            $model = new Link();  
            $linkGroups = Link::find()->select('group')->asArray()->distinct()->all(); //distinct();
            //$linkGroups->orderBy('group');
            //print_r($linkGroups);
            //return;
                
            if ($model->load(Yii::$app->request->post())) {
                $user_id = Yii::$app->user->identity->id;
                $model->last_edit = date('Y-m-d H:i:s');
                $model->created_by = $user_id;
                if (!empty($_POST['newGroup'])) {
                    $model->group = $_POST['newGroup'];
                }

                $model->pdfFile = UploadedFile::getInstance($model, 'pdfFile');
                if ($model->validate()) {
                    if ($model->save(false)) {
                        //save link before uploading attachment so we have a ID to link to
                        if ($model->pdfFile) {
                            if ($model->upload($model->id, $model->label)) { 
                                Yii::$app->session->setFlash('success', "Link created successfully with attachment: " . $model->pdfFile->name);
                                return $this->redirect(['/']);
                            } 
                        } else {
                            //no error, no attachment
                            Yii::$app->session->setFlash('success', "Link created successfully.");
                            return $this->redirect(['/']);
                        }
                        
                    } else {
                        Yii::$app->session->setFlash('error', "Link failed to save.");
                        return $this->redirect(['/']);
                    }
                    
                } else {
                    //Html::error($model,'group')
                    Yii::$app->session->setFlash('error', "Validation failed. Please Review Form.");
                }
                
            }

            return $this->render('create', [
                'model' => $model,
                'linkGroups'  => $linkGroups
            ]);
        } else {
            throw new ForbiddenHttpException('You do not have permission to access this page.');
        }
    }

    /**
     * Updates an existing Link model.
     * Only allow update if you created it ( unless superAdmin)
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->can('update_link')) {
            $model = $this->findModel($id);
            $linkGroups = Link::find()->select('group')->asArray()->distinct()->all();
            
            //find document if exists
            $pdfFileInfo = $model->getAttachment($id);

            if ($model->load(Yii::$app->request->post())) {
                $user_id = Yii::$app->user->identity->id;
                $model->last_edit = date('Y-m-d H:i:s');
                $model->created_by = $user_id;
                if (!empty($_POST['newGroup'])) {
                    $model->group = $_POST['newGroup'];
                }

                $model->pdfFile = UploadedFile::getInstance($model, 'pdfFile');
                if ($model->validate()) {
                    if ($model->save(false)) {
                        //save link before uploading attachment so we have a ID to link to
                        if ($model->pdfFile) {
                            if ($model->upload($model->id, $model->label)) { 
                                Yii::$app->session->setFlash('success', "Link updated successfully with attachment: " . $model->pdfFile->name);
                                return $this->redirect(['/']);
                            } 
                        } else {
                            //no error, no attachment
                            Yii::$app->session->setFlash('success', "Link updated successfully.");
                            return $this->redirect(['/']);
                        }
                        
                    } else {
                        Yii::$app->session->setFlash('error', "Link failed to update.");
                        return $this->redirect(['/']);
                    }
                    
                } else {
                    //Html::error($model,'group')
                    Yii::$app->session->setFlash('error', "Validation failed. Please Review Form.");
                }
            }

            if (!empty($pdfFileInfo)) {
                $model->pdfFile = '<a target="_blank" href="'. Yii::getAlias('@web') .'/'. $pdfFileInfo['path'] . $pdfFileInfo['name'].'"><i class="far fa-file-pdf"></i>' . ' ('.$this->formatBytes($pdfFileInfo['size']).')</a>';
            }
            return $this->render('update', [
                'model' => $model,
                'linkGroups' => $linkGroups
            ]);

        } else {
            throw new ForbiddenHttpException('You do not have permission to access this page.');
        }
    }

    public function formatBytes($bytes, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
    
        // Uncomment one of the following alternatives
         $bytes /= pow(1024, $pow);
         $bytes /= (1 << (10 * $pow)); 
    
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    } 

    /**
     * Deletes an existing Link model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->type == 'file') {
            if ($model->deleteAttachment($id)) {
                $model->delete();
                Yii::$app->session->setFlash('success', "Successfully deleted link and associated attachment.");
            } else {
                Yii::$app->session->setFlash('error', "An error occured while deleting link attachment.");
            }
        } else {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', "Link deleted successfully.");
        }

        return $this->redirect(['/']);
    }

    /**
     * Finds the Link model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Link the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Link::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
